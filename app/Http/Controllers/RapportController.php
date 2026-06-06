<?php

namespace App\Http\Controllers;

use App\Services\RapportWordService;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;

class RapportController extends Controller
{
    public function view()
    {
        return view('rapport.index');
    }

    public function download()
    {
        $pdf = Pdf::loadView('rapport.index')
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => false,
                'dpi'                  => 96,
            ]);

        $dompdf = $pdf->getDomPDF();
        $canvas  = $dompdf->get_canvas();

        $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
            $font  = $fontMetrics->get_font('Helvetica');
            $text  = 'Page | ' . $pageNumber;
            $w     = $canvas->get_width();
            $h     = $canvas->get_height();
            $tw    = $fontMetrics->get_text_width($text, $font, 9);
            $canvas->text($w - $tw - 55, $h - 30, $text, $font, 9, [0, 0, 0]);
        });

        return $pdf->download('rapport_pfe_smilecare.pdf');
    }

    public function downloadWord()
    {
        $phpWord = (new RapportWordService())->build();

        $temp = tempnam(sys_get_temp_dir(), 'smilecare_rapport');
        IOFactory::createWriter($phpWord, 'Word2007')->save($temp);

        return response()
            ->download($temp, 'rapport_pfe_smilecare.docx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->deleteFileAfterSend(true);
    }
}
