<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\DocumentVersion;
use App\Models\Lead;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\ProjectDocument;
use App\Models\ProjectPhase;
use App\Models\Quote;
use App\Models\QuoteVersion;
use App\Models\User;
use App\Models\VisualAsset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', env('ADMIN_EMAIL', 'admin@fabstudio.local'))->firstOrFail();

        $client = Client::firstOrCreate(
            ['email' => 'cliente.demo@fabstudio.local'],
            [
                'name' => 'Cliente Demo FAB STUDIO',
                'phone' => '+57 300 000 0000',
                'city' => 'Popayan',
                'notes' => 'Cliente de demostracion para validar el flujo MVP.',
            ],
        );

        $clientRole = Role::firstOrCreate(['name' => 'client']);

        $clientUser = User::updateOrCreate(
            ['email' => 'cliente.demo@fabstudio.local'],
            [
                'client_id' => $client->id,
                'name' => 'Cliente Demo FAB STUDIO',
                'password' => Hash::make('password'),
            ],
        );

        $clientUser->forceFill(['email_verified_at' => now()])->save();

        if (! $clientUser->hasRole($clientRole)) {
            $clientUser->assignRole($clientRole);
        }

        if (Project::where('code', 'FAB-0001')->exists()) {
            return;
        }

        $lead = Lead::factory()
            ->converted($client)
            ->create([
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'source' => 'landing',
                'interest' => 'vivienda bioclimatica',
            ]);

        $project = Project::factory()
            ->for($client)
            ->create([
                'lead_id' => $lead->id,
                'code' => 'FAB-0001',
                'name' => 'Casa Bioclimatica Demo',
                'typology' => 'residencial',
                'status' => 'active',
                'current_phase' => 'Anteproyecto',
                'location' => 'Popayan, Cauca',
            ]);

        $phase = ProjectPhase::factory()
            ->for($project)
            ->create([
                'name' => 'Anteproyecto',
                'status' => 'in_progress',
                'sort_order' => 1,
            ]);

        Milestone::factory()
            ->for($project)
            ->create([
                'project_phase_id' => $phase->id,
                'title' => 'Entrega de primera propuesta',
                'status' => 'pending',
                'sort_order' => 1,
            ]);

        $document = ProjectDocument::factory()
            ->for($project)
            ->create([
                'uploaded_by_id' => $admin->id,
                'title' => 'Plano conceptual inicial',
                'category' => 'design',
                'visibility' => 'client',
                'status' => 'published',
            ]);

        DocumentVersion::factory()
            ->for($document, 'document')
            ->create([
                'uploaded_by_id' => $admin->id,
                'version_number' => 1,
                'original_name' => 'plano-conceptual-v1.pdf',
            ]);

        $asset = VisualAsset::factory()
            ->for($project)
            ->create([
                'uploaded_by_id' => $admin->id,
                'title' => 'Render fachada principal',
                'type' => 'render',
                'visibility' => 'client',
                'status' => 'published',
                'sort_order' => 1,
            ]);

        $quote = Quote::factory()
            ->for($project)
            ->for($client)
            ->create([
                'created_by_id' => $admin->id,
                'quote_number' => 'COT-0001',
                'title' => 'Propuesta inicial de diseno',
                'status' => 'review',
            ]);

        QuoteVersion::factory()
            ->for($quote)
            ->create([
                'created_by_id' => $admin->id,
                'version_number' => 1,
                'status' => 'review',
            ]);

        ProjectComment::factory()
            ->for($project)
            ->for($admin, 'user')
            ->for($asset, 'commentable')
            ->create([
                'type' => 'comment',
                'visibility' => 'internal',
                'body' => 'Render demo asociado al portal cliente.',
            ]);
    }
}
