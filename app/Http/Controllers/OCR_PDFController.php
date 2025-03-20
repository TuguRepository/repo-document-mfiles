<?php

namespace App\Http\Controllers;

use App\Services\PDFWatermarkService;
use Illuminate\Http\Request;

class OCR_PDFController extends Controller
{
    protected $pdfWatermarkService;

    public function __construct(PDFWatermarkService $pdfWatermarkService)
    {
        $this->pdfWatermarkService = $pdfWatermarkService;
    }

    public function watermark(Request $request)
    {
        $pdfContent = file_get_contents($request->file('pdf')->path());
        $isDownload = $request->input('is_download', false);
        $username = $request->input('username', 'User');

        $watermarkedPdf = $this->pdfWatermarkService->processWithQPDF($pdfContent, $isDownload, $username);

        return response($watermarkedPdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="watermarked_document.pdf"',
        ]);
    }
}
