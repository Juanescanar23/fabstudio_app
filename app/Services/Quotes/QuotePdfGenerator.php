<?php

namespace App\Services\Quotes;

use App\Models\QuoteVersion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class QuotePdfGenerator
{
    public function generate(QuoteVersion $version, string $disk = 'local'): string
    {
        $version->loadMissing(['quote.client', 'quote.project', 'createdBy', 'reviewedBy', 'approvedBy', 'template']);

        $quote = $version->quote;
        $number = $quote?->quote_number ?: 'cotizacion-'.$quote?->getKey();
        $filename = Str::slug($number.'-v'.$version->version_number).'.pdf';
        $path = 'quotes/'.$filename;

        $pdf = Pdf::loadView('quotes.pdf', [
            'quote' => $quote,
            'version' => $version,
            'content' => $version->content ?: [],
        ])->setPaper('letter');

        Storage::disk($disk)->put($path, $pdf->output());

        return $path;
    }
}
