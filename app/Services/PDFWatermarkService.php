<?php

namespace App\Services;

use TCPDF;
use setasign\Fpdi\Tcpdf\Fpdi;

class PDFWatermarkService
{
    /**
     * Menambahkan watermark "Controlled Copy" pada PDF
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

                // Atur transparansi
                $pdf->SetAlpha(0.3);

                // Atur font
                $pdf->SetFont('helvetica', 'B', 60);
                $pdf->SetTextColor(200, 200, 200);

                // Hitung posisi tengah halaman
                $pageWidth = $pdf->getPageWidth();
                $pageHeight = $pdf->getPageHeight();
                $centerX = $pageWidth / 2;
                $centerY = $pageHeight / 2;

                // Rotasi dan posisikan teks watermark
                $pdf->StartTransform();
                $pdf->Rotate(45, $centerX, $centerY);
                $pdf->Text($centerX - 50, $centerY, 'Controlled Copy');
                $pdf->StopTransform();

                // Tambahkan info pengguna jika bukan download
                if (!$isDownload) {
                    $pdf->SetAlpha(1);
                    $pdf->SetTextColor(100, 100, 100);
                    $pdf->SetFont('helvetica', '', 8);
                    $pdf->Text(10, $pageHeight - 10, "Viewed by: {$username} on " . date('Y-m-d H:i:s'));
                }
            }

            // Output ke string
            $watermarkedPdf = $pdf->Output('', 'S');

            // Verifikasi bahwa kita mendapatkan PDF yang valid
            if (substr($watermarkedPdf, 0, 4) !== '%PDF') {
                \Log::warning('Generated watermarked content may not be a valid PDF');
                return $pdfContent; // Return original if watermarking fails
            }

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
     * Hitung jumlah halaman dalam PDF (cadangan jika FPDI gagal)
     */
    private function countPagesInPdf($pdfContent)
    {
        // Cara sederhana untuk menghitung jumlah halaman (estimasi)
        preg_match_all('/\/Page\s*<</', $pdfContent, $matches);
        $count = count($matches[0]);

        // Jika tidak menemukan halaman, coba cara lain
        if ($count === 0) {
            preg_match_all('/\/Type\s*\/Page[^s]/', $pdfContent, $matches);
            $count = count($matches[0]);
        }

        // Minimal satu halaman
        return max(1, $count);
    }

    /**
     * Metode alternatif menggunakan qpdf jika tersedia
     */
    public function processWithQPDF($pdfContent, $isDownload, $username)
    {
        if (!$this->isQpdfAvailable()) {
            \Log::warning('qpdf not available, using FPDI method instead');
            return $this->processWithWatermark($pdfContent, $isDownload, $username);
        }

        // Buat file temporary untuk input, watermark, dan output
        $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf_in_');
        $tempWatermarkFile = tempnam(sys_get_temp_dir(), 'pdf_wm_');
        $tempOutputFile = tempnam(sys_get_temp_dir(), 'pdf_out_');

        try {
            // Simpan konten PDF asli ke file temporary
            file_put_contents($tempInputFile, $pdfContent);

            // Buat watermark PDF
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('PDFWatermarkService');
            $pdf->SetAuthor('System');
            $pdf->SetTitle('Watermark Layer');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(0, 0, 0, true);
            $pdf->SetAutoPageBreak(false, 0);

            // Tambahkan halaman
            $pdf->AddPage();

            // Atur transparansi
            $pdf->SetAlpha(0.3);

            // Atur font
            $pdf->SetFont('helvetica', 'B', 60);
            $pdf->SetTextColor(200, 200, 200);

            // Hitung posisi tengah halaman
            $pageWidth = $pdf->getPageWidth();
            $pageHeight = $pdf->getPageHeight();
            $centerX = $pageWidth / 2;
            $centerY = $pageHeight / 2;

            // Rotasi dan posisikan teks
            $pdf->StartTransform();
            $pdf->Rotate(45, $centerX, $centerY);
            $pdf->Text($centerX - 50, $centerY, 'Controlled Copy');
            $pdf->StopTransform();

            // Tambahkan info pengguna jika bukan download
            if (!$isDownload) {
                $pdf->SetAlpha(1);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->Text(10, $pageHeight - 10, "Viewed by: {$username} on " . date('Y-m-d H:i:s'));
            }

            // Output watermark ke file
            $pdf->Output($tempWatermarkFile, 'F');

            // Gunakan qpdf untuk overlay
            $command = sprintf(
                'qpdf --overlay "%s" -- "%s" "%s"',
                $tempWatermarkFile,
                $tempInputFile,
                $tempOutputFile
            );

            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('qpdf overlay failed: ' . implode("\n", $output));
            }

            return file_get_contents($tempOutputFile);

        } catch (\Exception $e) {
            \Log::error('QPDF watermarking failed: ' . $e->getMessage());
            // Fallback to FPDI method
            return $this->processWithWatermark($pdfContent, $isDownload, $username);
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
     * Periksa apakah qpdf tersedia
     */
    private function isQpdfAvailable()
    {
        exec('which qpdf 2>/dev/null', $output, $returnVar);
        return $returnVar === 0;
    }
}
