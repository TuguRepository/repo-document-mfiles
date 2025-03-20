<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PDFWatermarkService
{
    /**
     * Menambahkan watermark "Controlled Copy" pada PDF gambar
     *
     * @param string $pdfContent Konten PDF asli
     * @param bool $isDownload Apakah ini permintaan download
     * @param string $username Username untuk tracking
     * @return string Konten PDF dengan watermark
     */
    public function processWithWatermark($pdfContent, $isDownload, $username)
    {
        // Buat file temporary untuk input
        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf_in_');

        try {
            // Simpan konten PDF ke file temporary
            file_put_contents($tempInputFile, $pdfContent);

            // Gunakan FPDI untuk mengimpor PDF yang ada
            $pdf = new Fpdi();
            $pdf->SetCreator('PDFWatermarkService');
            $pdf->SetAuthor('System');
            $pdf->SetTitle('Watermarked Document');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Hitung jumlah halaman dari file asli
            $pageCount = $pdf->setSourceFile($tempInputFile);

            // Untuk setiap halaman, tambahkan watermark di atas konten asli
            for ($i = 1; $i <= $pageCount; $i++) {
                // Import halaman dari PDF asli
                $templateId = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($templateId);

                // Tambahkan halaman dengan ukuran yang sama dengan halaman asli
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // Gunakan halaman yang diimpor sebagai template
                $pdf->useTemplate($templateId);

                // Atur transparansi untuk watermark utama
                $pdf->SetAlpha(0.3);

                // Atur font untuk Controlled Copy
                $pdf->SetFont('helvetica', 'B', 60);
                $pdf->SetTextColor(200, 200, 200);

                // Hitung posisi tengah halaman
                $pageWidth = $pdf->getPageWidth();
                $pageHeight = $pdf->getPageHeight();

                // Tambahkan banyak teks "Controlled Copy" pada halaman
                for ($x = 0; $x < $pageWidth; $x += 150) {
                    for ($y = 0; $y < $pageHeight; $y += 100) {
                        // Rotasi dan posisikan teks watermark di grid
                        $pdf->StartTransform();
                        $pdf->Rotate(45, ($x + 75), ($y + 50));
                        $pdf->Text($x, $y, 'Controlled Copy');
                        $pdf->StopTransform();
                    }
                }
            }

            // Output ke string
            $watermarkedPdf = $pdf->Output('', 'S');

            return $watermarkedPdf;

        } catch (\Exception $e) {
            \Log::error('PDF watermarking error: ' . $e->getMessage());
            return $pdfContent; // Return original if watermarking fails
        } finally {
            // Bersihkan file temporary
            if (file_exists($tempInputFile)) {
                @unlink($tempInputFile);
            }
        }
    }

    /**
     * Menambahkan watermark "Controlled Copy" pada PDF OCR menggunakan qpdf
     *
     * @param string $pdfContent Konten PDF asli
     * @param bool $isDownload Apakah ini permintaan download
     * @param string $username Username untuk tracking
     * @return string Konten PDF dengan watermark
     */
    public function processWithQPDF($pdfContent, $isDownload, $username)
    {
        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf_in_');
        $tempWatermarkFile = tempnam(sys_get_temp_dir(), 'pdf_wm_');
        $tempOutputFile = tempnam(sys_get_temp_dir(), 'pdf_out_');

        try {
            file_put_contents($tempInputFile, $pdfContent);

            $watermarkText = 'Controlled Copy';
            $this->createWatermarkPDF($tempWatermarkFile, $watermarkText);

            $command = [
                'qpdf',
                '--overlay',
                $tempWatermarkFile,
                '--',
                $tempInputFile,
                $tempOutputFile
            ];

            $process = new Process($command);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \Exception('qpdf overlay failed: ' . $process->getErrorOutput());
            }

            $watermarkedPdf = file_get_contents($tempOutputFile);

            return $watermarkedPdf;

        } catch (\Exception $e) {
            \Log::error('QPDF watermarking failed: ' . $e->getMessage());
            return $pdfContent; // Return original if watermarking fails
        } finally {
            // Bersihkan file temporary
            foreach ([$tempInputFile, $tempWatermarkFile, $tempOutputFile] as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Membuat file PDF watermark
     *
     * @param string $outputFile Path untuk menyimpan file watermark PDF
     * @param string $text Teks watermark
     */
    private function createWatermarkPDF($outputFile, $text)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('PDFWatermarkService');
        $pdf->SetAuthor('System');
        $pdf->SetTitle('Watermark Layer');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0, true);
        $pdf->SetAutoPageBreak(false, 0);

        $pdf->AddPage();

        $pdf->SetAlpha(0.3);
        $pdf->SetFont('helvetica', 'B', 60);
        $pdf->SetTextColor(200, 200, 200);

        $pageWidth = $pdf->getPageWidth();
        $pageHeight = $pdf->getPageHeight();

        for ($x = 0; $x < $pageWidth; $x += 150) {
            for ($y = 0; $y < $pageHeight; $y += 100) {
                $pdf->StartTransform();
                $pdf->Rotate(45, $x + 75, $y + 50);
                $pdf->Text($x, $y, $text);
                $pdf->StopTransform();
            }
        }

        $pdf->Output($outputFile, 'F');
    }
}
