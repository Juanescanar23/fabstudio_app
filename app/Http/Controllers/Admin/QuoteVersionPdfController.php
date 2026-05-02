<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuoteVersionPdfController extends Controller
{
    public function __invoke(Request $request, QuoteVersion $version): StreamedResponse
    {
        abort_unless($request->user()?->hasAnyRole(['super_admin', 'admin']), 403);
        abort_unless(filled($version->pdf_path), 404);

        $disk = $version->pdf_disk ?: 'local';
        abort_unless(Storage::disk($disk)->exists($version->pdf_path), 404);

        $version->loadMissing('quote');

        return Storage::disk($disk)->download(
            $version->pdf_path,
            ($version->quote?->quote_number ?: 'cotizacion').'-v'.$version->version_number.'.pdf',
        );
    }
}
