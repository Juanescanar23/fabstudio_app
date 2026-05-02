<?php

namespace App\Services\Hostinger;

use JsonException;
use RuntimeException;

class HostingerZoneFile
{
    public static function read(string $path): array
    {
        $resolvedPath = self::resolvePath($path);

        if (! is_file($resolvedPath)) {
            throw new RuntimeException("No existe el archivo DNS: {$path}");
        }

        try {
            $payload = json_decode((string) file_get_contents($resolvedPath), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException("El archivo DNS no contiene JSON valido: {$exception->getMessage()}");
        }

        if (! is_array($payload)) {
            throw new RuntimeException('El archivo DNS debe contener un objeto JSON o un arreglo de registros.');
        }

        if (isset($payload['zone']) && is_array($payload['zone'])) {
            return $payload['zone'];
        }

        return $payload;
    }

    private static function resolvePath(string $path): string
    {
        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return $path;
        }

        return base_path($path);
    }
}
