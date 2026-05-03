<?php

namespace App\Console\Commands;

use App\Models\QuoteTemplate;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ProductionReadinessCheck extends Command
{
    protected $signature = 'app:readiness-check
        {--json : Mostrar resultado en JSON}
        {--strict : Devolver fallo si existen advertencias}';

    protected $description = 'Valida condiciones tecnicas minimas antes de entrega o despliegue.';

    public function handle(): int
    {
        $checks = collect([
            $this->checkAppKey(),
            $this->checkAppUrl(),
            $this->checkDebugMode(),
            $this->checkDatabase(),
            $this->checkCriticalTables(),
            $this->checkAdminUser(),
            $this->checkAdminTwoFactor(),
            $this->checkStorageWritable(),
            $this->checkQueueInfrastructure(),
            $this->checkMailer(),
            $this->checkSecureSession(),
            $this->checkQuoteTemplates(),
            $this->checkAutomationInfrastructure(),
            $this->checkBackupPolicy(),
            $this->checkDefaultAdminPassword(),
        ]);

        if ($this->option('json')) {
            $this->line($checks->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        } else {
            $this->table(
                ['Estado', 'Chequeo', 'Detalle'],
                $checks->map(fn (array $check): array => [
                    strtoupper($check['status']),
                    $check['name'],
                    $check['detail'],
                ])->all(),
            );
        }

        $hasFailures = $checks->contains(fn (array $check): bool => $check['status'] === 'fail');
        $hasWarnings = $checks->contains(fn (array $check): bool => $check['status'] === 'warn');

        if ($hasFailures || ($this->option('strict') && $hasWarnings)) {
            $this->error('Readiness check no aprobado.');

            return self::FAILURE;
        }

        $this->info($hasWarnings
            ? 'Readiness check aprobado con advertencias.'
            : 'Readiness check aprobado.');

        return self::SUCCESS;
    }

    /**
     * @return array{name: string, status: string, detail: string}
     */
    private function check(string $name, string $status, string $detail): array
    {
        return compact('name', 'status', 'detail');
    }

    private function checkAppKey(): array
    {
        return filled(Config::get('app.key'))
            ? $this->check('APP_KEY', 'pass', 'Llave de aplicacion configurada.')
            : $this->check('APP_KEY', 'fail', 'Falta APP_KEY.');
    }

    private function checkAppUrl(): array
    {
        $url = (string) Config::get('app.url');

        if (app()->isProduction() && ! str_starts_with($url, 'https://')) {
            return $this->check('APP_URL', 'fail', 'En produccion debe usar HTTPS.');
        }

        return filled($url)
            ? $this->check('APP_URL', 'pass', $url)
            : $this->check('APP_URL', 'warn', 'APP_URL vacio.');
    }

    private function checkDebugMode(): array
    {
        if (app()->isProduction() && Config::get('app.debug')) {
            return $this->check('APP_DEBUG', 'fail', 'APP_DEBUG no puede estar activo en produccion.');
        }

        return $this->check('APP_DEBUG', 'pass', Config::get('app.debug') ? 'Activo fuera de produccion.' : 'Inactivo.');
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();
        } catch (Throwable $exception) {
            return $this->check('Base de datos', 'fail', 'No hay conexion: '.$exception->getMessage());
        }

        return $this->check('Base de datos', 'pass', 'Conexion disponible: '.DB::connection()->getName());
    }

    private function checkCriticalTables(): array
    {
        $missingTables = collect([
            'users',
            'roles',
            'jobs',
            'failed_jobs',
            'cache_locks',
            'clients',
            'projects',
            'project_documents',
            'document_versions',
            'visual_assets',
            'quotes',
            'quote_versions',
            'quote_templates',
            'automation_logs',
            'leads',
        ])->reject(fn (string $table): bool => Schema::hasTable($table));

        return $missingTables->isEmpty()
            ? $this->check('Tablas criticas', 'pass', 'Todas las tablas esperadas existen.')
            : $this->check('Tablas criticas', 'fail', 'Faltan: '.$missingTables->implode(', '));
    }

    private function checkAdminUser(): array
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('model_has_roles')) {
            return $this->check('Usuario admin', 'fail', 'Tablas de usuarios/roles no disponibles.');
        }

        $exists = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['super_admin', 'admin']))
            ->exists();

        return $exists
            ? $this->check('Usuario admin', 'pass', 'Existe al menos un usuario administrativo.')
            : $this->check('Usuario admin', 'fail', 'No existe usuario con rol super_admin/admin.');
    }

    private function checkAdminTwoFactor(): array
    {
        if (! Schema::hasTable('users') || ! Schema::hasTable('model_has_roles')) {
            return $this->check('2FA admin', 'fail', 'Tablas de usuarios/roles no disponibles.');
        }

        $adminUsers = User::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['super_admin', 'admin']))
            ->get(['id', 'email', 'two_factor_secret', 'two_factor_confirmed_at']);

        if ($adminUsers->isEmpty()) {
            return $this->check('2FA admin', 'fail', 'No hay usuarios administrativos para validar.');
        }

        $withoutTwoFactor = $adminUsers
            ->reject(fn (User $user): bool => filled($user->two_factor_secret) && filled($user->two_factor_confirmed_at))
            ->pluck('email');

        return $withoutTwoFactor->isEmpty()
            ? $this->check('2FA admin', 'pass', 'Todos los usuarios administrativos tienen 2FA confirmado.')
            : $this->check('2FA admin', 'warn', 'Admins sin 2FA confirmado: '.$withoutTwoFactor->implode(', '));
    }

    private function checkStorageWritable(): array
    {
        $path = storage_path('framework/readiness-check.tmp');

        try {
            file_put_contents($path, now()->toIso8601String());
            @unlink($path);
        } catch (Throwable $exception) {
            return $this->check('Storage', 'fail', 'No se puede escribir en storage: '.$exception->getMessage());
        }

        return $this->check('Storage', 'pass', 'Storage escribible.');
    }

    private function checkQueueInfrastructure(): array
    {
        $queue = (string) Config::get('queue.default');

        if (app()->isProduction() && $queue === 'sync') {
            return $this->check('Colas', 'fail', 'QUEUE_CONNECTION no debe ser sync en produccion.');
        }

        if ($queue === 'database' && (! Schema::hasTable('jobs') || ! Schema::hasTable('failed_jobs'))) {
            return $this->check('Colas', 'fail', 'Faltan tablas jobs/failed_jobs para cola database.');
        }

        return $this->check('Colas', 'pass', 'QUEUE_CONNECTION='.$queue.'.');
    }

    private function checkMailer(): array
    {
        $mailer = (string) Config::get('mail.default');

        if (in_array($mailer, ['log', 'array'], true)) {
            return $this->check('Correo transaccional', 'warn', 'Mailer actual: '.$mailer.'. Configurar proveedor real antes de enviar emails a clientes.');
        }

        return $this->check('Correo transaccional', 'pass', 'Mailer configurado: '.$mailer.'.');
    }

    private function checkSecureSession(): array
    {
        if (app()->isProduction() && ! Config::get('session.secure')) {
            return $this->check('Sesion segura', 'fail', 'SESSION_SECURE_COOKIE debe estar activo en produccion.');
        }

        return $this->check('Sesion segura', 'pass', Config::get('session.secure') ? 'Cookies seguras activas.' : 'Cookies seguras no requeridas fuera de produccion.');
    }

    private function checkQuoteTemplates(): array
    {
        if (! Schema::hasTable('quote_templates')) {
            return $this->check('Plantillas cotizacion', 'fail', 'Tabla quote_templates no existe.');
        }

        $count = QuoteTemplate::query()->where('status', 'active')->count();

        return $count > 0
            ? $this->check('Plantillas cotizacion', 'pass', $count.' plantilla(s) activa(s).')
            : $this->check('Plantillas cotizacion', 'warn', 'No hay plantillas activas.');
    }

    private function checkAutomationInfrastructure(): array
    {
        if (! Schema::hasTable('automation_logs')) {
            return $this->check('Automatizaciones', 'fail', 'Tabla automation_logs no existe.');
        }

        if (! Config::get('fabstudio.automations.enabled')) {
            return $this->check('Automatizaciones', 'warn', 'Automatizaciones desactivadas por configuracion.');
        }

        return $this->check('Automatizaciones', 'pass', 'Motor operativo activo y tabla de logs disponible.');
    }

    private function checkBackupPolicy(): array
    {
        if (! app()->isProduction()) {
            return $this->check('Backups', 'pass', 'Politica de backups validable en produccion.');
        }

        $provider = (string) Config::get('fabstudio.backups.provider');

        return filled($provider)
            ? $this->check('Backups', 'pass', 'Proveedor/politica declarada: '.$provider.'.')
            : $this->check('Backups', 'warn', 'Declarar FABSTUDIO_BACKUP_PROVIDER y retencion antes del cierre formal.');
    }

    private function checkDefaultAdminPassword(): array
    {
        if (app()->isProduction() && env('ADMIN_PASSWORD') === 'password') {
            return $this->check('Password admin', 'fail', 'ADMIN_PASSWORD mantiene el valor inicial.');
        }

        return $this->check('Password admin', 'pass', 'No se detecto password inicial en configuracion.');
    }
}
