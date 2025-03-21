<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PDFWatermarkService;
use Intervention\Image\ImageManager;
use App\Facades\UserContext;
use Illuminate\Support\Facades\Http;
use TCPDF;
use Image;

class STKDocumentController extends Controller

{
    protected $mfilesUrl = 'https://mf.tugu.com/REST';

    /**
     * Show the STK document listing page
     */
    public function index()
    {
        return view('stk.index');
    }

    /**
     * Get STK documents from M-Files
     */
    public function getDocuments(Request $request)
    {
        try {
            // Log the request parameters
            Log::info('STK Document search initiated', [
                'search' => $request->input('search'),
                'sort' => $request->input('sort'),
                'page' => $request->input('page'),
                'limit' => $request->input('limit')
            ]);

            // Get the authentication token
            $authToken = $this->getAuthToken();

            if (!$authToken) {
                Log::error('M-Files authentication failed - no token returned');
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal autentikasi dengan M-Files'
                ], 401);
            }

            // Default parameters
            $limit = $request->input('limit', 20);
            $page = $request->input('page', 1);
            $searchQuery = $request->input('search', '');
            $sortOrder = $request->input('sort', 'modified_desc');

            // The STK Class ID is 153 as provided
            $classId = 153;

            // Construct the M-Files API endpoint
            $objectsUrl = "{$this->mfilesUrl}/objects";

            // Build search criteria for M-Files
            $searchParams = [
                'limit' => $limit,
                'page' => $page,
                'properties' => []
            ];

            // Add class filter
            $searchParams['properties'][] = [
                'propertyDef' => 100, // Class property
                'typedValue' => [
                    'dataType' => 9, // Class lookup
                    'value' => $classId
                ]
            ];

            // Add search query if provided
            if (!empty($searchQuery)) {
                // Name or content contains the search query
                $searchParams['searchConditions'] = [
                    'condition' => 'OR',
                    'conditions' => [
                        [
                            'typedValue' => [
                                'dataType' => 1, // Text
                                'value' => $searchQuery
                            ],
                            'operator' => 'contains',
                            'expression' => [
                                'propertyDef' => 0 // Name or title
                            ]
                        ],
                        [
                            'typedValue' => [
                                'dataType' => 1, // Text
                                'value' => $searchQuery
                            ],
                            'operator' => 'contains',
                            'expression' => [
                                'propertyDef' => -102 // Content or file content
                            ]
                        ]
                    ]
                ];
            }

            // Add sorting based on user selection
            $sortParams = [];
            switch ($sortOrder) {
                case 'title_asc':
                    $sortParams[] = [
                        'propertyDef' => 0, // Name/title
                        'sortAscending' => true
                    ];
                    break;
                case 'title_desc':
                    $sortParams[] = [
                        'propertyDef' => 0, // Name/title
                        'sortAscending' => false
                    ];
                    break;
                case 'modified_asc':
                    $sortParams[] = [
                        'propertyDef' => 21, // Last modified
                        'sortAscending' => true
                    ];
                    break;
                default: // modified_desc
                    $sortParams[] = [
                        'propertyDef' => 21, // Last modified
                        'sortAscending' => false
                    ];
                    break;
            }

            if (!empty($sortParams)) {
                $searchParams['sortResults'] = $sortParams;
            }

            // Execute the search
            Log::info('Executing M-Files search', [
                'searchParams' => json_encode($searchParams)
            ]);

            $searchResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Authentication' => $authToken,
            ])->post($objectsUrl, $searchParams);

            if (!$searchResponse->successful()) {
                Log::error('M-Files search failed', [
                    'status' => $searchResponse->status(),
                    'response' => $searchResponse->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melakukan pencarian dokumen: ' . ($searchResponse->json()['message'] ?? 'Unknown error')
                ], $searchResponse->status());
            }

            $searchResults = $searchResponse->json();

            // Transform the results into our document format
            $documents = [];
            $total = $searchResults['TotalCount'] ?? 0;

            foreach ($searchResults['Items'] ?? [] as $item) {
                $objId = $item['objVer']['objID'] ?? null;
                $version = $item['objVer']['version'] ?? null;

                if (!$objId) {
                    continue;
                }

                // Extract properties
                $properties = [];
                foreach ($item['properties'] ?? [] as $property) {
                    $propertyDef = $property['propertyDef'] ?? 0;
                    $dataValue = null;

                    // Extract property value based on data type
                    if (isset($property['typedValue'])) {
                        $dataType = $property['typedValue']['dataType'] ?? 0;

                        switch ($dataType) {
                            case 1: // Text
                            case 2: // Integer
                            case 3: // Float
                            case 5: // Date
                            case 6: // Time
                            case 7: // Timestamp
                                $dataValue = $property['typedValue']['value'] ?? null;
                                break;
                            case 9: // Lookup
                            case 10: // Multi-select lookup
                                if (isset($property['typedValue']['lookupValues'])) {
                                    $lookupValues = [];
                                    foreach ($property['typedValue']['lookupValues'] as $lookup) {
                                        $lookupValues[] = $lookup['displayValue'] ?? $lookup['lookupID'] ?? '';
                                    }
                                    $dataValue = implode(', ', $lookupValues);
                                } else {
                                    $dataValue = $property['typedValue']['displayValue'] ?? '';
                                }
                                break;
                            default:
                                $dataValue = $property['typedValue']['displayValue'] ?? $property['typedValue']['value'] ?? null;
                        }
                    }

                    $properties[$propertyDef] = $dataValue;
                }

                // Map properties to our document structure
                $title = $properties[0] ?? 'Untitled Document'; // Name property
                $documentNumber = $properties[1870] ?? $objId; // NamePropertyDef from your class definition
                $createdDate = $properties[20] ?? ''; // Created
                $modifiedDate = $properties[21] ?? ''; // Last modified
                $documentType = $properties[1002] ?? ''; // Document type property (adjust if needed)
                $businessArea = $properties[1003] ?? ''; // Business area property (adjust if needed)

                $documents[] = [
                    'id' => $objId,
                    'version' => $version,
                    'title' => $title,
                    'document_number' => $documentNumber,
                    'created_date' => $createdDate,
                    'modified_date' => $modifiedDate,
                    'class' => 'STK',
                    'nomor_stk' => $documentNumber,
                    'jenis_stk' => $documentType,
                    'judul_stk' => $businessArea,
                ];
            }

            Log::info('M-Files search completed', [
                'total' => $total,
                'documents_count' => count($documents)
            ]);

            return response()->json([
                'success' => true,
                'documents' => $documents,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('STK document retrieval error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error sistem: ' . $e->getMessage()
            ], 500);
        }
    }

     /**
 * Get file content from M-Files with watermark for PDF files
 */
public function getFileContent(Request $request, $objectId, $version = 'latest')
{
    try {
        // Log the file retrieval request
        Log::info('M-Files file content request', [
            'objectId' => $objectId,
            'version' => $version,
            'url' => $this->mfilesUrl
        ]);

        // Get the authentication token
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            Log::error('M-Files authentication failed - no token returned (file content)');
            return response()->json([
                'success' => false,
                'message' => 'Gagal autentikasi dengan M-Files'
            ], 401);
        }

        // Check if this is a download request
        $isDownload = $request->input('download', false);

        // Step 1: First, let's fetch the object and file metadata
        $objectUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/latest";

        Log::info('Fetching object metadata', [
            'objectUrl' => $objectUrl
        ]);

        $objectResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($objectUrl);

        if (!$objectResponse->successful()) {
            Log::error('M-Files object metadata fetch failed', [
                'status' => $objectResponse->status(),
                'response' => $objectResponse->body()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil metadata objek: ' . ($objectResponse->json()['message'] ?? 'Unknown error')
            ], $objectResponse->status());
        }

        $objectData = $objectResponse->json();
        $objectTitle = $objectData['title'] ?? $objectData['Title'] ?? 'document';

        // Get file metadata
        $filesUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/latest/files";

        $filesResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($filesUrl);

        if (!$filesResponse->successful()) {
            Log::error('M-Files files metadata fetch failed');
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil metadata file'
            ], $filesResponse->status());
        }

        $filesData = $filesResponse->json();

        // Check if we have any files
        if (empty($filesData) || !is_array($filesData)) {
            Log::error('No files found in object');
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada file yang ditemukan untuk dokumen ini'
            ], 404);
        }

        // Get the first file's ID and name
        $firstFile = $filesData[0] ?? null;

        if (!$firstFile) {
            Log::error('Unable to access first file data');
            return response()->json([
                'success' => false,
                'message' => 'Data file tidak valid'
            ], 404);
        }

        $fileId = $firstFile['ID'] ?? null;
        $fileName = $firstFile['NameOrTitle'] ?? $firstFile['Name'] ?? $firstFile['Title'] ?? 'document';
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!$fileId) {
            Log::error('File ID not found in first file data');
            return response()->json([
                'success' => false,
                'message' => 'ID file tidak ditemukan'
            ], 404);
        }

        // Step 2: Get the file content
        $contentUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/latest/files/{$fileId}/content";
        Log::info('Fetching file content', ['contentUrl' => $contentUrl]);

        $contentResponse = Http::withHeaders([
            'X-Authentication' => $authToken,
        ])->get($contentUrl);

        if (!$contentResponse->successful() || strlen($contentResponse->body()) == 0) {
            Log::error('M-Files file content fetch failed');

            // Try alternative URL pattern
            $alternativeUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/{$version}/files/{$fileId}/content";
            Log::info('Trying alternative URL', ['alternativeUrl' => $alternativeUrl]);

            $contentResponse = Http::withHeaders([
                'X-Authentication' => $authToken,
            ])->get($alternativeUrl);

            if (!$contentResponse->successful() || strlen($contentResponse->body()) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil konten file'
                ], 404);
            }
        }

        // Get content type and length
        $contentType = $contentResponse->header('Content-Type');
        $contentLength = strlen($contentResponse->body());

        Log::info('Successfully retrieved file content', [
            'contentType' => $contentType,
            'contentLength' => $contentLength,
            'fileName' => $fileName
        ]);

        // Override content type if needed based on file extension
        if ($contentType === 'application/x-zip-compressed' && $fileExtension) {
            $detectedMimeType = $this->getMimeTypeForExtension($fileExtension);
            if ($detectedMimeType && $detectedMimeType !== 'application/x-zip-compressed') {
                $contentType = $detectedMimeType;
            }
        }

        // Apply watermarking for PDF files
        if ($contentType === 'application/pdf') {
            // Get the username
            // $username = $request->session()->get('username', '');
            $payload = UserContext::getPayload();
            $username = $payload['name'];

            // // If username is empty, try to get from auth
            // if (empty($username) && auth()->check()) {
            //     $username = auth()->user()->name ?? auth()->user()->username ?? '';
            // }
            // Log username for debugging
            Log::info('Using username for watermark', ['username' => $username]);


                // Check if this is a download request
            $isDownload = $request->input('download', false) ||
            $request->route()->getName() === 'stk.documents.download';

                    try {
                        // Get the original content
                        $originalContent = $contentResponse->body();

                        // Verify content is truly a PDF
                        if (substr($originalContent, 0, 4) !== '%PDF') {
                            Log::warning('Content may not be a valid PDF', [
                                'content_start' => bin2hex(substr($originalContent, 0, 20))
                            ]);
                        }

                        // Apply watermark
                        $watermarkService = new \App\Services\PDFWatermarkService();
                        $fileContent = $watermarkService->processWithWatermark($originalContent, $isDownload, $username);

                        // Verify we got valid content back
                        if (empty($fileContent) || strlen($fileContent) < 100) {
                            Log::error('Watermarking returned invalid content');
                            $fileContent = $originalContent;
                        }

                        Log::info('Watermark applied', [
                            'isDownload' => $isDownload,
                            'contentLength' => strlen($fileContent)
                        ]);

                        // Set content disposition based on request type
                        $contentDisposition = $isDownload ? 'attachment' : 'inline';

                        // Return the watermarked PDF
                        return response($fileContent, 200)
                            ->header('Content-Type', $contentType)
                            ->header('Content-Disposition', $contentDisposition . '; filename="' . $fileName . '"')
                            ->header('Content-Length', strlen($fileContent))
                            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

                    } catch (\Exception $e) {
                        // Log watermarking error
                        Log::error('PDF watermarking failed', ['error' => $e->getMessage()]);

                        // Fallback to original PDF
                        Log::info('Returning original PDF without watermark');
                        return response($contentResponse->body(), 200)
                            ->header('Content-Type', $contentType)
                            ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                            ->header('Content-Length', $contentLength);
                    }
                }

                // For non-PDF files, return as-is
                return response($contentResponse->body(), 200)
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
                    ->header('Content-Length', $contentLength);

            } catch (\Exception $e) {
                Log::error('M-Files file content retrieval error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Error sistem: ' . $e->getMessage()
                ], 500);
            }
        }

    public function addWatermarkBase64(Request $request)
    {
        $manager = new ImageManager();

        // Ambil Base64 dari request
        $base64String = $request->input('image_base64');

        // Decode Base64 ke binary
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));

        // Baca gambar dari binary
        $image = $manager->read($imageData);

        // Tambahkan teks watermark "CONFIDENTIAL"
        $image->draw()->text(
            text: 'CONFIDENTIAL',
            x: $image->width() / 2,  // Tengah gambar
            y: $image->height() / 2,
            options: [
                'font' => public_path('fonts/arial.ttf'), // Pastikan font ada
                'size' => 50,
                'color' => 'rgba(255, 0, 0, 0.5)', // Warna merah transparan
                'align' => 'center',
                'valign' => 'middle',
            ]
        );

        // Konversi kembali ke Base64
        $watermarkedBase64 = base64_encode($image->toPng());

        return response()->json([
            'message' => 'Watermark added',
            'image_base64' => 'data:image/png;base64,' . $watermarkedBase64,
]);
    }


        private function applyWatermarkDirect($pdfContent, $isDownload = false, $username = 'User')
    {
        try {
            // Gunakan TCPDF langsung
            $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->SetCreator('PDF Watermark');
            $pdf->SetAuthor('System');
            $pdf->SetTitle('Watermarked Document');
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(0, 0, 0, true);
            $pdf->SetAutoPageBreak(false, 0);

            // Buat 1 halaman watermark
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
            $pdf->Text($centerX - 120, $centerY, 'Copy');
            $pdf->StopTransform();

            // Tambahkan info pengguna jika bukan download
            if (!$isDownload) {
                $pdf->SetAlpha(1);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->SetFont('helvetica', '', 8);
                $pdf->Text(10, $pageHeight - 10, "Viewed by: {$username} on " . date('Y-m-d H:i:s'));
            }

            // Output ke string
            return $pdf->Output('', 'S');
        } catch (\Exception $e) {
            \Log::error('Direct watermarking error: ' . $e->getMessage());
            // Gagal watermark, kembalikan PDF asli
            return $pdfContent;
        }
    }



/**
 * Helper method to get MIME type for a file extension
 */
private function getMimeTypeForExtension($extension)
{
    $extension = strtolower($extension);

    $mimeTypes = [
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'txt' => 'text/plain',
        'csv' => 'text/csv',
        'html' => 'text/html',
        'htm' => 'text/html',
        'xml' => 'application/xml',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        '7z' => 'application/x-7z-compressed',
        'mp3' => 'audio/mpeg',
        'mp4' => 'video/mp4',
        'avi' => 'video/x-msvideo',
        'wmv' => 'video/x-ms-wmv',
        'rtf' => 'application/rtf'
    ];

    return $mimeTypes[$extension] ?? 'application/octet-stream';
}

/**
 * Helper method to get file extension for a MIME type
 */
private function getExtensionForMimeType($mimeType)
{
    $mimeToExt = [
        'application/pdf' => 'pdf',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'text/plain' => 'txt',
        'text/csv' => 'csv',
        'text/html' => 'html',
        'application/xml' => 'xml',
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'application/x-rar-compressed' => 'rar',
        'application/x-7z-compressed' => '7z',
        'audio/mpeg' => 'mp3',
        'video/mp4' => 'mp4',
        'video/x-msvideo' => 'avi',
        'video/x-ms-wmv' => 'wmv',
        'application/rtf' => 'rtf'
    ];

    return $mimeToExt[$mimeType] ?? '';
}
/**
 * Get document preview with improved handling
 */
public function getPreview(Request $request, $objectId, $version = 'latest')
{
    try {
        Log::info('Document preview request', [
            'objectId' => $objectId,
            'version' => $version
        ]);

        // Get the file content directly
        $fileResponse = $this->getFileContent($request, $objectId, $version);

        // Check if the response is an error
        if ($fileResponse->getStatusCode() !== 200 ||
            strpos($fileResponse->headers->get('Content-Type'), 'application/json') !== false) {

            // If it's JSON, it's likely an error response
            $responseData = json_decode($fileResponse->getContent(), true);

            Log::error('Preview error - getFileContent returned error', [
                'status' => $fileResponse->getStatusCode(),
                'response' => $responseData
            ]);

            return $fileResponse; // Return the original error
        }

        // Get content type to determine if we need special handling
        $contentType = $fileResponse->headers->get('Content-Type');

        // For PDF, images, and text-based content, we can return directly
        if (
            strpos($contentType, 'application/pdf') !== false ||
            strpos($contentType, 'image/') !== false ||
            strpos($contentType, 'text/') !== false
        ) {
            Log::info('Preview - returning viewable content directly', [
                'contentType' => $contentType
            ]);

            return $fileResponse;
        }

        // For other content types that can't be previewed directly in the browser,
        // we'll return a JSON response indicating this
        Log::info('Preview - file type not directly previewable', [
            'contentType' => $contentType
        ]);

        return response()->json([
            'success' => false,
            'previewable' => false,
            'message' => 'File ini tidak dapat ditampilkan langsung di browser. Silakan download file untuk melihatnya.',
            'contentType' => $contentType
        ]);

    } catch (\Exception $e) {
        Log::error('Document preview error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error sistem: ' . $e->getMessage()
        ], 500);
    }
}

    public function debugConnection()
    {
        try {
            $authToken = $this->getAuthToken();

            if (!$authToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed - could not retrieve token'
                ], 401);
            }

            // Test connection by getting vault properties
            $vaultUrl = "{$this->mfilesUrl}/structure/classes";

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Authentication' => $authToken,
            ])->get($vaultUrl);

            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection test failed',
                    'status' => $response->status(),
                    'response' => $response->body()
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => 'Connection successful',
                'vault_info' => [
                    'url' => $this->mfilesUrl,
                    'properties_count' => count($response->json() ?? [])
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection test error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }


//    Masih ga ada watermark


/**
 * Fungsi untuk menambahkan watermark ke PDF tanpa menambah halaman kosong
 *
 * @param string $pdfContent Konten PDF asli
 * @param bool $isDownload Apakah ini permintaan download
 * @param string $username Username untuk tracking
 * @return string Konten PDF dengan watermark
 */
private function addWatermarkToPdf($pdfContent, $isDownload = false, $username = 'User')
{
    // Buat file temporary untuk input
    $tempInputFile = tempnam(sys_get_temp_dir(), 'pdf_in_');
    file_put_contents($tempInputFile, $pdfContent);

    try {
        // Gunakan FPDI untuk mengimpor PDF asli
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

            // Tambahkan banyak teks "Controlled Copy" pada halaman dalam pola grid
            for ($x = 0; $x < $pageWidth; $x += 150) {
                for ($y = 0; $y < $pageHeight; $y += 100) {
                    // Rotasi dan posisikan teks watermark di grid
                    $pdf->StartTransform();
                    $pdf->Rotate(45, ($x + 75), ($y + 50));
                    $pdf->Text($x, $y, 'Controlled Copy');
                    $pdf->StopTransform();
                }
            }

            // Tambahkan info pengguna jika bukan download (tidak menambah halaman baru)
            if (!$isDownload) {
                // Kembalikan transparansi ke normal untuk teks detail pengguna
                $pdf->SetAlpha(1);
                $pdf->SetTextColor(100, 100, 100);
                $pdf->SetFont('helvetica', '', 8);
                // Tambahkan teks di bagian bawah halaman (tidak menambah halaman baru)
                $footerText = "Viewed by: {$username} on " . date('Y-m-d H:i:s');
                $footerX = 10;
                $footerY = ($pageHeight - 10);
                $pdf->Text($footerX, $footerY, $footerText);
            }
        }

        // Output ke string
        return $pdf->Output('', 'S');

    } catch (\Exception $e) {
        \Log::error('PDF watermarking failed: ' . $e->getMessage());
        return $pdfContent; // Return original if watermarking fails
    } finally {
        // Bersihkan file temporary
        if (file_exists($tempInputFile)) {
            @unlink($tempInputFile);
        }
    }
}

/**
 * Handle document download request
 *
 * @param Request $request
 * @param int $objectId Document object ID
 * @param string $version Document version
 * @return Response
 */
public function downloadDocument(Request $request, $objectId, $version = 'latest')
{
    try {
        // Log download request
        Log::info('Document download request', [
            'objectId' => $objectId,
            'version' => $version,
            'user' => $request->session()->get('username', 'Unknown')
        ]);

        // Explicitly set download flag to true
        $request->merge(['download' => true]);

        // Reuse existing getFileContent function to retrieve the file with watermark
        $response = $this->getFileContent($request, $objectId, $version);

        // If response is JSON (error), return as-is
        if ($response->headers->get('Content-Type') === 'application/json') {
            return $response;
        }

        // Extract filename from Content-Disposition header
        $contentDisposition = $response->headers->get('Content-Disposition');
        $fileName = 'document';

        if ($contentDisposition && preg_match('/filename="([^"]+)"/', $contentDisposition, $matches)) {
            $fileName = $matches[1];
        } elseif ($contentDisposition && preg_match('/filename=([^\s;]+)/', $contentDisposition, $matches)) {
            $fileName = $matches[1];
        }

        // Make sure Content-Disposition is set to attachment for download
        $response->headers->remove('Content-Disposition');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        // Add cache control headers
        $response->headers->set('Cache-Control', 'private, no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        Log::info('Document download processed successfully', [
            'objectId' => $objectId,
            'fileName' => $fileName
        ]);

        return $response;
    } catch (\Exception $e) {
        Log::error('Document download failed', [
            'objectId' => $objectId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengunduh dokumen: ' . $e->getMessage()
        ], 500);
    }
}

   /**
 * Get STK documents summary with improved document type categorization
 */
public function getSTKSummary(Request $request)
{
    try {
        // Get pagination parameters with type casting dan validasi
        $page = max(1, (int) $request->input('page', 1));
        $perPage = max(5, min(100, (int) $request->input('perPage', 10)));

        // Log pagination parameters untuk debugging
        Log::info('STK Summary: Pagination request', [
            'page' => $page,
            'perPage' => $perPage
        ]);

        // Get the authentication token from the request header
        $authToken = $request->header('X-Authentication');

        // If no token in header, try to get it from backend
        if (!$authToken) {
            $authToken = $this->getAuthToken();

            if (!$authToken) {
                Log::error('STK Summary: Authentication failed - no token available');
                return response()->json([
                    'success' => false,
                    'message' => 'Autentikasi gagal - tidak dapat mengambil token',
                    'authentication_required' => true  // Changed colon (:) to arrow (=>)
                ], 401);
            }
        }

        // Get class info first
        $classUrl = "{$this->mfilesUrl}/structure/classes/153";
        $classResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($classUrl);

        if (!$classResponse->successful()) {
            Log::error('STK Summary: Class info fetch failed', [
                'status' => $classResponse->status(),
                'response' => $classResponse->body()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil informasi kelas: ' . ($classResponse->json()['Message'] ?? 'Unknown error')
            ], $classResponse->status());
        }

        $classInfo = $classResponse->json();

        // Use the simple query parameter approach that works
        $objectsUrl = "{$this->mfilesUrl}/objects.aspx?p100=153";

        Log::info('STK Summary: Fetching documents using simple query parameter', [
            'url' => $objectsUrl
        ]);

        $searchResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($objectsUrl);

        if (!$searchResponse->successful()) {
            Log::error('STK Summary: Document search failed', [
                'status' => $searchResponse->status(),
                'response' => $searchResponse->body()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil dokumen: ' . ($searchResponse->json()['Message'] ?? 'Unknown error'),
                'class_info' => [
                    'class_id' => $classInfo['ID'] ?? 0,
                    'class_name' => $classInfo['Name'] ?? 'STK',
                    'name_property_def' => $classInfo['NamePropertyDef'] ?? 0
                ]
            ], $searchResponse->status());
        }

        $searchResults = $searchResponse->json();
        $totalDocuments = $searchResults['TotalCount'] ?? count($searchResults['Items'] ?? []);

        // Process documents for breakdown
        $documentsByType = [];
        $documentsByYear = [];
        $latestDocuments = [];
        $documents = [];

        // Define document type mapping
        $documentTypeMap = [
            'A' => 'Pedoman',
            'B' => 'Tata Kerja Organisasi',
            'C' => 'Tata Kerja Individu',
            'D' => 'BPCP',
            'SOP' => 'Sistem & Prosedur'
        ];

        foreach ($searchResults['Items'] ?? [] as $item) {
            try {
                // Simpan data sederhana dari respons API dengan validasi
                $objId = $item['ObjVer']['ID'] ?? null;
                $version = $item['ObjVer']['Version'] ?? null;
                $title = $item['Title'] ?? 'Untitled Document';
                $displayId = $item['DisplayID'] ?? '';
                $createdDate = $item['Created'] ?? '';
                $modifiedDate = $item['LastModified'] ?? '';

                if (!$objId) {
                    continue;
                }

                // Ekstrak nomor dokumen, jenis, dan tahun dari title
                $nomor = '';
                $jenis = 'Tidak Dikategorikan';
                $tahun = '';
                $jenisKode = '';

                // New regex pattern to match format: [A-D]-xxx/xxxx/yyyy
                if (preg_match('/([A-D])-(\d+)\/([^\/]+)\/(\d{4})/', $title, $matches)) {
                    $jenisKode = $matches[1];
                    $nomor = $matches[0]; // Full document number
                    $tahun = $matches[4]; // Year

                    // Map the document type code to its description
                    $jenis = $documentTypeMap[$jenisKode] ?? 'Tidak Dikategorikan';
                }

                // Build the document data
                $docData = [
                    'id' => $objId,
                    'version' => $version,
                    'title' => $title,
                    'document_number' => $nomor ?: $displayId,
                    'jenis_stk' => $jenis,
                    'jenis_kode' => $jenisKode,
                    'tahun' => $tahun,
                    'created_date' => $createdDate,
                    'modified_date' => $modifiedDate
                ];

                $documents[] = $docData;

                // Group by document type
                if (!empty($jenis)) {
                    if (!isset($documentsByType[$jenis])) {
                        $documentsByType[$jenis] = 0;
                    }
                    $documentsByType[$jenis]++;
                }

                // Extract year from document number first, then from created date as fallback
                if (!empty($tahun)) {
                    if (!isset($documentsByYear[$tahun])) {
                        $documentsByYear[$tahun] = 0;
                    }
                    $documentsByYear[$tahun]++;
                } else if (!empty($createdDate)) {
                    try {
                        $dateObj = \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $createdDate);
                        if ($dateObj) {
                            $year = $dateObj->format('Y');

                            if (!isset($documentsByYear[$year])) {
                                $documentsByYear[$year] = 0;
                            }
                            $documentsByYear[$year]++;
                        }
                    } catch (\Exception $dateException) {
                        Log::warning('Failed to parse date: ' . $createdDate);
                    }
                }

                // Add to latest documents (keep only 5)
                if (count($latestDocuments) < 10) {
                    $latestDocuments[] = $docData;
                }
            } catch (\Exception $itemException) {
                Log::warning('Error processing document item', [
                    'message' => $itemException->getMessage(),
                    'item' => $item
                ]);
                continue; // Skip this item and continue with the next
            }
        }

        // Sort years in descending order
        krsort($documentsByYear);

        // Sort latest documents by modified date
        usort($latestDocuments, function($a, $b) {
            $dateA = $a['modified_date'] ?? '';
            $dateB = $b['modified_date'] ?? '';
            return strcmp($dateB, $dateA); // Descending order
        });

        // Pagination logic
        $totalItems = count($documents);
        $totalPages = max(1, ceil($totalItems / $perPage)); // Minimal 1 halaman
        $currentPage = min(max(1, $page), $totalPages);
        $offset = ($currentPage - 1) * $perPage;

        // Get subset of documents for current page
        $paginatedDocuments = array_slice($documents, $offset, $perPage);

        // Log pagination result
        Log::info('STK Summary: Pagination result', [
            'total_items' => $totalItems,
            'total_pages' => $totalPages,
            'current_page' => $currentPage,
            'per_page' => $perPage,
            'documents_on_page' => count($paginatedDocuments)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data dokumen STK berhasil diambil',
            'class_info' => [
                'class_id' => $classInfo['ID'] ?? 0,
                'class_name' => $classInfo['Name'] ?? 'STK',
                'name_property_def' => $classInfo['NamePropertyDef'] ?? 0
            ],
            'summary' => [
                'total_documents' => $totalDocuments,
                'documents_by_type' => $documentsByType,
                'documents_by_year' => $documentsByYear,
                'latest_documents' => array_slice($latestDocuments, 0, 10)
            ],
            'documents' => $paginatedDocuments,
            'pagination' => [
                'total' => $totalItems,
                'per_page' => (int) $perPage,
                'current_page' => (int) $currentPage,
                'total_pages' => (int) $totalPages
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('STK Summary: Error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error saat mengambil data: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Get authentication token from M-Files
     */
    private function getAuthToken()
    {
        try {
            // Get credentials from config
            $credentials = [
                'Username' => config('mfiles.username'),
                'Password' => config('mfiles.password'),
                'VaultGuid' => config('mfiles.vault_guid')
            ];

            // Log authentication attempt (tanpa password)
            Log::info('M-Files authentication attempt', [
                'username' => $credentials['Username'],
                'vault_guid' => $credentials['VaultGuid']
            ]);

            // Create session
            $sessionUrl = "{$this->mfilesUrl}/session.aspx?_method=PUT";
            $sessionResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->put($sessionUrl, $credentials);

            if (!$sessionResponse->successful()) {
                Log::error('M-Files session creation failed', [
                    'status' => $sessionResponse->status(),
                    'response' => $sessionResponse->body()
                ]);
                return null;
            }

            $sessionData = $sessionResponse->json();

            Log::info('M-Files session created', [
                'session_id_exists' => isset($sessionData['SessionID'])
            ]);

            // Get token
            $authUrl = "{$this->mfilesUrl}/server/authenticationtokens";
            $tokenResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Authentication' => $sessionData['Authentication'] ?? '',
                'X-SessionKey' => $sessionData['SessionID'] ?? '',
            ])->post($authUrl, $credentials);

            if (!$tokenResponse->successful()) {
                Log::error('M-Files token retrieval failed', [
                    'status' => $tokenResponse->status(),
                    'response' => $tokenResponse->body()
                ]);
                return null;
            }

            $tokenData = $tokenResponse->json();

            Log::info('M-Files token retrieved', [
                'token_exists' => isset($tokenData['Value'])
            ]);

            return $tokenData['Value'] ?? null;

        } catch (\Exception $e) {
            Log::error('M-Files authentication error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }



    public function showCategoryPedoman()
    {

        $payload = UserContext::getPayload();

        if (!isset($payload['partner'])) {
            $payload['partner'] = '9900000002';
            $payload['name'] = 'Admin';
        }

        $bp = $payload['partner'];

        $urlEndpoint = env('TKYC_URL', 'http://localhost:3004')."/api/image/{$bp}";

        $response = Http::get($urlEndpoint);

        if ($response->successful() && isset($response->json()['data']['image_data'])) {
            $imageData = $response->json()['data']['image_data'];
        } else {
            $imageData = null;
        }


        return view('stk.category', [
            'categoryTitle' => 'Pedoman',
            'categoryDescription' => 'Dokumen yang berisi peraturan dan pedoman Tugu Insurance sebagai panduan pelaksanaan kegiatan dan program kerja.',
            'categoryCode' => 'A',
            'namaUser' => $payload['name'],
            'jobTitle' => $payload['job_title'],
            'imageData' => $imageData,
        ]);
    }

    /**
     * Tampilkan halaman kategori Tata Kerja Organisasi
     */
    public function showCategoryTKO()
    {
        return view('stk.category', [
            'categoryTitle' => 'Tata Kerja Organisasi',
            'categoryDescription' => 'Dokumen yang mengatur tata kerja organisasi dan hubungan antar unit kerja di Tugu Insurance.',
            'categoryCode' => 'B'
        ]);
    }

    /**
     * Tampilkan halaman kategori Tata Kerja Individu
     */
    public function showCategoryTKI()
    {
        return view('stk.category', [
            'categoryTitle' => 'Tata Kerja Individu',
            'categoryDescription' => 'Dokumen yang mengatur tata kerja individu dalam menjalankan tugas dan tanggung jawabnya.',
            'categoryCode' => 'C'
        ]);
    }

    /**
     * Tampilkan halaman kategori BPCP
     */
    public function showCategoryBPCP()
    {
        return view('stk.category', [
            'categoryTitle' => 'BPCP',
            'categoryDescription' => 'Dokumen Batasan Pelayanan dan Catatan Prosedur yang mengatur standar operasional dan prosedur pelayanan.',
            'categoryCode' => 'D'
        ]);
    }

    /**
     * Tampilkan halaman kategori SOP
     */
    public function showCategorySOP()
    {
        return view('stk.category', [
            'categoryTitle' => 'Sistem & Prosedur',
            'categoryDescription' => 'Dokumen yang berisi sistem dan prosedur operasional standar Tugu Insurance.',
            'categoryCode' => 'SOP'
        ]);
    }

    /**
     * Tampilkan dokumen berdasarkan tahun
     */
    public function showDocumentsByYear($year)
    {
        return view('stk.year', [
            'year' => $year,
            'title' => "Dokumen Tahun $year",
            'description' => "Daftar semua dokumen STK yang diterbitkan pada tahun $year."
        ]);
    }


    /**
     * Get documents by category for API
     */
    public function getDocumentsByCategory(Request $request)
    {
        try {
            // Get parameters
            $category = $request->input('category');
            $page = max(1, (int) $request->input('page', 1));
            $perPage = max(5, min(100, (int) $request->input('perPage', 10)));
            $sort = $request->input('sort', 'newest');
            $search = $request->input('search', '');
            $years = $request->input('years', '');

            // Get authentication token
            $authToken = $this->getAuthToken();

            if (!$authToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Autentikasi gagal - tidak dapat mengambil token',
                    'authentication_required' => true
                ], 401);
            }



            // Build query based on category
$query = '';
switch ($category) {
    case 'A':
        $query = "A-";
        break;
    case 'B':
        $query = "B-";
        break;
    case 'C':
        // Buat query lebih spesifik untuk kategori C
        $query = "C- \"Tata Kerja Individu\"";
        // Atau gunakan tahun terbaru untuk membatasi hasil
        // $query = "C- 2025";
        break;
    case 'D':
        $query = "D-";
        break;
    case 'SOP':
        $query = "SOP";
        break;
    default:
        $query = "";
}

            // Add search term if provided
            if (!empty($search)) {
                $query .= " " . $search;
            }

            // Make API call to M-Files
            $objectsUrl = "{$this->mfilesUrl}/objects.aspx?p100=153&q=" . urlencode($query);

            $searchResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Authentication' => $authToken,
            ])->get($objectsUrl);

            // dd($searchResponse);

            if (!$searchResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil dokumen: ' . ($searchResponse->json()['Message'] ?? 'Unknown error')
                ], $searchResponse->status());
            }

            $searchResults = $searchResponse->json();
            $allDocuments = [];
            $documentsByYear = [];

            // Define document type mapping (same as in getSTKSummary)
            $documentTypeMap = [
                'A' => 'Pedoman',
                'B' => 'Tata Kerja Organisasi',
                'C' => 'Tata Kerja Individu',
                'D' => 'BPCP',
                'SOP' => 'Sistem & Prosedur'
            ];

            // Process documents
            foreach ($searchResults['Items'] ?? [] as $item) {
                $objId = $item['ObjVer']['ID'] ?? null;
                $version = $item['ObjVer']['Version'] ?? null;
                $title = $item['Title'] ?? 'Untitled Document';
                $displayId = $item['DisplayID'] ?? '';
                $createdDate = $item['Created'] ?? '';
                $modifiedDate = $item['LastModified'] ?? '';

                if (!$objId) {
                    continue;
                }

                // Extract document number, type, and year from title
                $nomor = '';
                $jenis = 'Tidak Dikategorikan';
                $tahun = '';
                $jenisKode = '';

                // New regex pattern to match format: [A-D]-xxx/xxxx/yyyy
                if (preg_match('/([A-D])-(\d+)\/([^\/]+)\/(\d{4})/', $title, $matches)) {
                    $jenisKode = $matches[1];
                    $nomor = $matches[0]; // Full document number
                    $tahun = $matches[4]; // Year

                    // Map the document type code to its description
                    $jenis = $documentTypeMap[$jenisKode] ?? 'Tidak Dikategorikan';
                }

                // Skip if not matching the requested category
                if ($category !== $jenisKode && !($category === 'SOP' && stripos($title, 'SOP') !== false)) {
                    continue;
                }

                // Skip if year filter is applied and not in selected years
                if (!empty($years)) {
                    $yearArray = explode(',', $years);
                    if (!empty($tahun) && !in_array($tahun, $yearArray)) {
                        continue;
                    }
                }

                $docData = [
                    'id' => $objId,
                    'version' => $version,
                    'title' => $title,
                    'document_number' => $nomor ?: $displayId,
                    'jenis_stk' => $jenis,
                    'jenis_kode' => $jenisKode,
                    'tahun' => $tahun,
                    'created_date' => $createdDate,
                    'modified_date' => $modifiedDate
                ];

                $allDocuments[] = $docData;

                // Group by year for filters
                if (!empty($tahun)) {
                    if (!isset($documentsByYear[$tahun])) {
                        $documentsByYear[$tahun] = 0;
                    }
                    $documentsByYear[$tahun]++;
                }
            }

            // Sort documents based on sort parameter
            switch ($sort) {
                case 'newest':
                    usort($allDocuments, function($a, $b) {
                        return strcmp($b['modified_date'], $a['modified_date']);
                    });
                    break;
                case 'oldest':
                    usort($allDocuments, function($a, $b) {
                        return strcmp($a['modified_date'], $b['modified_date']);
                    });
                    break;
                case 'a-z':
                    usort($allDocuments, function($a, $b) {
                        return strcmp($a['title'], $b['title']);
                    });
                    break;
                case 'z-a':
                    usort($allDocuments, function($a, $b) {
                        return strcmp($b['title'], $a['title']);
                    });
                    break;
            }

            // Apply pagination
            $totalDocuments = count($allDocuments);
            $totalPages = max(1, ceil($totalDocuments / $perPage));
            $currentPage = min(max(1, $page), $totalPages);
            $offset = ($currentPage - 1) * $perPage;

            $paginatedDocuments = array_slice($allDocuments, $offset, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Data dokumen STK berhasil diambil',
                'documents' => $paginatedDocuments,
                'years' => $documentsByYear,
                'total' => $totalDocuments,
                'pagination' => [
                    'total' => $totalDocuments,
                    'per_page' => (int) $perPage,
                    'current_page' => (int) $currentPage,
                    'total_pages' => (int) $totalPages
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saat mengambil data: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
 * Get featured documents for homepage
 */
public function getFeaturedDocuments()
{
    try {
        // Get authentication token
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi gagal'
            ], 401);
        }

        // Get current year
        $currentYear = date('Y');

        // Create array to store featured documents
        $featuredDocs = [];

        // Get documents from last 3 years (1 document per year)
        for ($i = 0; $i < 3; $i++) {
            $year = $currentYear - $i;

            // Query for documents from this year
            $objectsUrl = "{$this->mfilesUrl}/objects.aspx?p100=153&q=" . urlencode($year);

            $searchResponse = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Authentication' => $authToken,
            ])->get($objectsUrl);

            if ($searchResponse->successful()) {
                $searchResults = $searchResponse->json();

                // Get first document from this year that matches the pattern
                foreach ($searchResults['Items'] ?? [] as $item) {
                    $title = $item['Title'] ?? 'Untitled Document';

                    // Check if title contains the year
                    if (strpos($title, (string)$year) !== false) {
                        $objId = $item['ObjVer']['ID'] ?? null;
                        $version = $item['ObjVer']['Version'] ?? null;

                        if ($objId) {
                            // Extract description - use what's after the year or a default
                            $description = 'Dokumen STK';
                            if (preg_match('/\d{4}[^\w]+(.*)/i', $title, $matches)) {
                                $description = trim($matches[1]);
                            }

                            $featuredDocs[] = [
                                'id' => $objId,
                                'version' => $version,
                                'title' => $title,
                                'description' => $description
                            ];

                            // Only get one document per year
                            break;
                        }
                    }
                }
            }

            // If we don't have 3 documents yet and this year failed, create a placeholder
            if (count($featuredDocs) <= $i) {
                $featuredDocs[] = [
                    'id' => 0,
                    'version' => 1,
                    'title' => "STK Tahun {$year}",
                    'description' => "Dokumen STK tahun {$year}"
                ];
            }
        }

        return response()->json([
            'success' => true,
            'documents' => $featuredDocs
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Search documents
 */
public function searchDocuments(Request $request)
{
    try {
        $query = $request->input('q', '');

        // Log search request
        Log::info('Document search request', [
            'query' => $query
        ]);

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query terlalu pendek. Minimal 2 karakter.',
                'documents' => []
            ]);
        }

        // Get authentication token
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            Log::error('Document search: Authentication failed - no token');
            return response()->json([
                'success' => false,
                'message' => 'Autentikasi gagal'
            ], 401);
        }

        // Query M-Files
        $objectsUrl = "{$this->mfilesUrl}/objects.aspx?p100=153&q=" . urlencode($query);

        Log::info('Document search: Querying M-Files', [
            'url' => $objectsUrl
        ]);

        $searchResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($objectsUrl);

        if (!$searchResponse->successful()) {
            Log::error('Document search: M-Files query failed', [
                'status' => $searchResponse->status(),
                'response' => $searchResponse->body()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil dokumen: ' . ($searchResponse->json()['Message'] ?? 'Unknown error')
            ], $searchResponse->status());
        }

        $searchResults = $searchResponse->json();

        Log::info('Document search: Got response', [
            'total_items' => count($searchResults['Items'] ?? [])
        ]);

        $documents = [];

        // Define document type mapping
        $documentTypeMap = [
            'A' => 'Pedoman',
            'B' => 'Tata Kerja Organisasi',
            'C' => 'Tata Kerja Individu',
            'D' => 'BPCP',
            'SOP' => 'Sistem & Prosedur'
        ];

        // Process documents
        foreach ($searchResults['Items'] ?? [] as $item) {
            $objId = $item['ObjVer']['ID'] ?? null;
            $version = $item['ObjVer']['Version'] ?? null;
            $title = $item['Title'] ?? 'Untitled Document';
            $displayId = $item['DisplayID'] ?? '';
            $createdDate = $item['Created'] ?? '';
            $modifiedDate = $item['LastModified'] ?? '';

            if (!$objId) {
                continue;
            }

            // Extract document number, type, and year
            $nomor = '';
            $jenis = 'Tidak Dikategorikan';
            $tahun = '';
            $jenisKode = '';

            if (preg_match('/([A-D])-(\d+)\/([^\/]+)\/(\d{4})/', $title, $matches)) {
                $jenisKode = $matches[1];
                $nomor = $matches[0]; // Full document number
                $tahun = $matches[4]; // Year

                // Map the document type code to its description
                $jenis = $documentTypeMap[$jenisKode] ?? 'Tidak Dikategorikan';
            }

            $documents[] = [
                'id' => $objId,
                'version' => $version,
                'title' => $title,
                'document_number' => $nomor ?: $displayId,
                'jenis_stk' => $jenis,
                'jenis_kode' => $jenisKode,
                'tahun' => $tahun,
                'created_date' => $createdDate,
                'modified_date' => $modifiedDate
            ];
        }

        // Sort by relevance (simplification: newest first)
        usort($documents, function($a, $b) {
            return strcmp($b['modified_date'], $a['modified_date']);
        });

        Log::info('Document search: Processed results', [
            'found_documents' => count($documents)
        ]);

        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => count($documents),
            'documents' => $documents
        ]);

    } catch (\Exception $e) {
        Log::error('Document search: Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
     }
    }

/**
 * Simple document search
 */
public function simpleSearchDocuments(Request $request)
{
    try {
        $query = $request->input('q', '');

        // Log pencarian untuk debugging
        \Log::info('Pencarian dokumen dengan query: ' . $query);

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Query terlalu pendek. Minimal 2 karakter.',
                'documents' => []
            ]);
        }

        // Get authentication token if needed
        $authToken = $this->getAuthToken();

        // Query API atau database
        $searchUrl = "{$this->mfilesUrl}/objects.aspx?p100=153&q=" . urlencode($query);

        $searchResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($searchUrl);

        if (!$searchResponse->successful()) {
            \Log::error('Pencarian gagal: ' . $searchResponse->status(), [
                'response' => $searchResponse->body()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan pencarian.',
                'documents' => []
            ]);
        }

        $searchResults = $searchResponse->json();
        $documents = [];

        // Document type mapping
        $documentTypeMap = [
            'A' => 'Pedoman',
            'B' => 'Tata Kerja Organisasi',
            'C' => 'Tata Kerja Individu',
            'D' => 'BPCP',
            'SOP' => 'Sistem & Prosedur'
        ];

        // Process documents
        foreach ($searchResults['Items'] ?? [] as $item) {
            $objId = $item['ObjVer']['ID'] ?? null;
            $version = $item['ObjVer']['Version'] ?? null;
            $title = $item['Title'] ?? 'Untitled Document';
            $displayId = $item['DisplayID'] ?? '';
            $createdDate = $item['Created'] ?? '';
            $modifiedDate = $item['LastModified'] ?? '';

            if (!$objId) continue;

            // Extract document number, type, and year
            $nomor = '';
            $jenis = 'Tidak Dikategorikan';
            $tahun = '';
            $jenisKode = '';

            if (preg_match('/([A-D])-(\d+)\/([^\/]+)\/(\d{4})/', $title, $matches)) {
                $jenisKode = $matches[1];
                $nomor = $matches[0];
                $tahun = $matches[4];
                $jenis = $documentTypeMap[$jenisKode] ?? 'Tidak Dikategorikan';
            }

            $documents[] = [
                'id' => $objId,
                'version' => $version,
                'title' => $title,
                'document_number' => $nomor ?: $displayId,
                'jenis_stk' => $jenis,
                'jenis_kode' => $jenisKode,
                'tahun' => $tahun,
                'created_date' => $createdDate,
                'modified_date' => $modifiedDate
            ];
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'total' => count($documents),
            'documents' => $documents
        ]);

    } catch (\Exception $e) {
        \Log::error('Error pada pencarian dokumen: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'documents' => []
        ]);
    }
}

    // Fungsi pencarian cadangan jika M-Files gagal
    private function fallbackSearch($query)
    {
        Log::info('Menggunakan pencarian cadangan untuk: ' . $query);

        // Implementasi pencarian sederhana berdasarkan data di database lokal
        // atau menggunakan pendekatan lain yang tidak bergantung pada M-Files

        return response()->json([
            'success' => true,
            'query' => $query,
            'message' => 'Menggunakan pencarian alternatif',
            'documents' => [] // Isi dengan dokumen dari sumber alternatif
        ]);
    }
    /**
 * Get document info for preview modal
 */
public function getDocumentInfo(Request $request, $objectId, $version = 'latest')
{
    try {
        // Get the authentication token
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal autentikasi dengan M-Files'
            ], 401);
        }

        // Fetch object metadata
        $objectUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/{$version}";

        $objectResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($objectUrl);

        if (!$objectResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan informasi dokumen'
            ], $objectResponse->status());
        }

        $objectData = $objectResponse->json();

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $objectId,
                'version' => $version,
                'title' => $objectData['title'] ?? $objectData['Title'] ?? "Dokumen #{$objectId}",
                'document_number' => $objectData['DisplayID'] ?? '',
                'created_date' => $objectData['Created'] ?? '',
                'modified_date' => $objectData['LastModified'] ?? ''
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
    }



/**
 * Mengambil metadata dokumen untuk kartu dokumen yang bisa dibuka/tutup
 *
 * @param Request $request
 * @param int $objectId ID Dokumen
 * @param string $version Versi Dokumen (default: latest)
 * @return \Illuminate\Http\JsonResponse
 */
public function getDocumentMetadata(Request $request, $objectId, $version = 'latest')
{
    // Increase the overall script execution timeout
    set_time_limit(60); // Set to 60 seconds instead of the default 30
    try {
        Log::info('Mengambil metadata dokumen - mulai', [
            'objectId' => $objectId,
            'version' => $version,
            'user_agent' => $request->header('User-Agent')
        ]);

        // Dapatkan token autentikasi
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            Log::error('Autentikasi M-Files gagal - tidak ada token yang dikembalikan');
            return response()->json([
                'success' => false,
                'message' => 'Gagal autentikasi dengan M-Files'
            ], 401);
        }

        Log::info('Autentikasi berhasil, token didapatkan');

        // Ambil objek dari M-Files
        $objectUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/{$version}";

        Log::info('Mengambil data objek', ['url' => $objectUrl]);

        $objectResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($objectUrl);

        Log::info('Respons objek diterima', [
            'status' => $objectResponse->status(),
            'content_type' => $objectResponse->header('Content-Type'),
            'length' => strlen($objectResponse->body())
        ]);

        if (!$objectResponse->successful()) {
            Log::error('Pengambilan objek M-Files gagal', [
                'status' => $objectResponse->status(),
                'response' => substr($objectResponse->body(), 0, 500)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data objek: ' . ($objectResponse->json()['Message'] ?? 'Kesalahan tidak diketahui')
            ], $objectResponse->status());
        }

        $objectData = $objectResponse->json();

        Log::info('Data objek berhasil di-parse', [
            'has_properties' => isset($objectData['Properties']),
            'properties_count' => isset($objectData['Properties']) ? count($objectData['Properties']) : 0,
            'title' => $objectData['Title'] ?? 'Tidak ada judul'
        ]);

        // Daftar semua PropertyDef yang mungkin ada untuk dokumen STK
        $requiredPropertyDefs = [
            1853 => true, 1854 => true, 1855 => true, 1870 => true,
            1856 => false, 1857 => false, 1859 => false, 1861 => false,
            1862 => false, 1863 => false, 1864 => false, 1904 => false,
            1905 => false, 1903 => false, 1777 => false, 20 => true,
            25 => true, 21 => true, 23 => true, 24 => true,
            89 => true, 22 => true, 101 => false, 30 => true,
            31 => true, 32 => true
        ];

        // PENTING: Inisialisasi variabel metadata di sini
        $metadata = [];

        // Dapatkan kelas dokumen untuk memahami definisi properti
        $classId = 153; // ID kelas STK
        $classUrl = "{$this->mfilesUrl}/structure/classes/{$classId}";

        Log::info('Mengambil data kelas', ['url' => $classUrl]);

        $classResponse = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get($classUrl);

        if (!$classResponse->successful()) {
            Log::warning('Pengambilan kelas M-Files gagal, melanjutkan dengan metadata terbatas', [
                'status' => $classResponse->status()
            ]);
            // Lanjutkan tanpa info kelas
        }

        $classInfo = $classResponse->successful() ? $classResponse->json() : null;

        // Proses definisi properti jika tersedia
        $propertyDefs = [];
        if ($classInfo && isset($classInfo['AssociatedPropertyDefs'])) {
            foreach ($classInfo['AssociatedPropertyDefs'] as $propDef) {
                // Dapatkan definisi properti dari M-Files
                $propDefId = $propDef['PropertyDef'];
                $propRequired = $propDef['Required'] ?? false;

                // Ambil detail definisi properti
                $propDefUrl = "{$this->mfilesUrl}/structure/properties/{$propDefId}";
                $propDefResponse = Http::withHeaders([
                    'Accept' => 'application/json',
                    'X-Authentication' => $authToken,
                ])->get($propDefUrl);

                if ($propDefResponse->successful()) {
                    $propDefData = $propDefResponse->json();
                    $propertyDefs[$propDefId] = [
                        'id' => $propDefId,
                        'name' => $propDefData['Name'] ?? "Properti {$propDefId}",
                        'data_type' => $propDefData['DataType'] ?? 0,
                        'required' => $propRequired,
                        'display_order' => array_search($propDef, $classInfo['AssociatedPropertyDefs'])
                    ];
                }
            }
        } else {
            // Jika tidak bisa mendapatkan definisi properti dari kelas, gunakan daftar properti yang diberikan
            foreach ($requiredPropertyDefs as $propId => $isRequired) {
                // Ambil detail definisi properti
                $propDefUrl = "{$this->mfilesUrl}/structure/properties/{$propId}";

                try {
                    $propDefResponse = Http::withHeaders([
                        'Accept' => 'application/json',
                        'X-Authentication' => $authToken,
                    ])->get($propDefUrl);

                    if ($propDefResponse->successful()) {
                        $propDefData = $propDefResponse->json();
                        $propertyDefs[$propId] = [
                            'id' => $propId,
                            'name' => $propDefData['Name'] ?? "Properti {$propId}",
                            'data_type' => $propDefData['DataType'] ?? 0,
                            'required' => $isRequired,
                            'display_order' => array_search($propId, array_keys($requiredPropertyDefs))
                        ];
                    } else {
                        Log::warning("Gagal mendapatkan definisi properti {$propId}: " . $propDefResponse->status());
                    }
                } catch (\Exception $e) {
                    Log::warning("Error saat mengambil properti {$propId}: " . $e->getMessage());
                }
            }
        }

        // Ekstrak metadata dari properti objek yang ada di respons API
        if (isset($objectData['Properties'])) {
            foreach ($objectData['Properties'] as $property) {
                $propertyId = $property['PropertyDef'] ?? null;

                if (!$propertyId) continue;

                // Dapatkan definisi properti jika tersedia
                $propertyDef = $propertyDefs[$propertyId] ?? null;
                $propertyName = $propertyDef ? $propertyDef['name'] : "Properti {$propertyId}";
                $dataType = $propertyDef ? $propertyDef['data_type'] : 0;
                $required = $propertyDef ? $propertyDef['required'] : false;
                $displayOrder = $propertyDef ? $propertyDef['display_order'] : 9999;

                // Ekstrak nilai berdasarkan tipe data
                $value = null;

                if (isset($property['Value'])) {
                    switch ($dataType) {
                        case 1: // Teks
                        case 2: // Integer
                        case 3: // Float
                        case 5: // Tanggal
                        case 6: // Waktu
                        case 7: // Timestamp
                            $value = $property['Value']['Value'] ?? null;
                            break;
                        case 9: // Lookup
                            $value = $property['Value']['DisplayValue'] ?? null;
                            break;
                        case 10: // Multi-select lookup
                            if (isset($property['Value']['Items'])) {
                                $values = [];
                                foreach ($property['Value']['Items'] as $item) {
                                    $values[] = $item['DisplayValue'] ?? '';
                                }
                                $value = implode(', ', $values);
                            }
                            break;
                        default:
                            $value = $property['Value']['DisplayValue'] ?? $property['Value']['Value'] ?? null;
                    }
                }

                // Format tipe field tertentu untuk tampilan yang lebih baik
                if ($dataType == 5 || $dataType == 7) { // Tanggal atau Timestamp
                    if ($value) {
                        try {
                            $date = new \DateTime($value);
                            $value = $date->format('d M Y');
                        } catch (\Exception $e) {
                            // Biarkan apa adanya jika parsing gagal
                        }
                    }
                }

                $metadata[$propertyId] = [
                    'name' => $propertyName,
                    'value' => $value,
                    'data_type' => $dataType,
                    'required' => $required,
                    'display_order' => $displayOrder
                ];
            }
        }

        // Ambil properti yang belum ada secara terpisah
                foreach ($requiredPropertyDefs as $propId => $isRequired) {
                    if (!isset($metadata[$propId])) {
                        // Properti ini belum ada, coba ambil secara terpisah
                        try {
                            // Properti URL di M-Files API
                            $propValueUrl = "{$this->mfilesUrl}/objects/0/{$objectId}/{$version}/properties/{$propId}";

                            Log::info("Mengambil properti terpisah: {$propId}", ['url' => $propValueUrl]);

                            // Add a timeout for individual property requests
                            $propValueResponse = Http::timeout(5)->withHeaders([
                                'Accept' => 'application/json',
                                'X-Authentication' => $authToken,
                            ])->get($propValueUrl);

                    if ($propValueResponse->successful()) {
                        $propValueData = $propValueResponse->json();
                        $propertyDef = $propertyDefs[$propId] ?? null;
                        $dataType = $propertyDef ? $propertyDef['data_type'] : 0;

                        // Ekstrak nilai
                        $value = null;
                        if (isset($propValueData['Value'])) {
                            switch ($dataType) {
                                case 1: // Teks
                                case 2: // Integer
                                case 3: // Float
                                case 5: // Tanggal
                                case 6: // Waktu
                                case 7: // Timestamp
                                    $value = $propValueData['Value']['Value'] ?? null;
                                    break;
                                case 9: // Lookup
                                    $value = $propValueData['Value']['DisplayValue'] ?? null;
                                    break;
                                case 10: // Multi-select lookup
                                    if (isset($propValueData['Value']['Items'])) {
                                        $values = [];
                                        foreach ($propValueData['Value']['Items'] as $item) {
                                            $values[] = $item['DisplayValue'] ?? '';
                                        }
                                        $value = implode(', ', $values);
                                    }
                                    break;
                                default:
                                    $value = $propValueData['Value']['DisplayValue'] ?? $propValueData['Value']['Value'] ?? null;
                            }
                        }

                        // Format tanggal jika perlu
                        if ($dataType == 5 || $dataType == 7) { // Tanggal atau Timestamp
                            if ($value) {
                                try {
                                    $date = new \DateTime($value);
                                    $value = $date->format('d M Y');
                                } catch (\Exception $e) {
                                    // Biarkan apa adanya jika parsing gagal
                                }
                            }
                        }

                        $metadata[$propId] = [
                            'name' => $propertyDef ? $propertyDef['name'] : "Properti {$propId}",
                            'value' => $value,
                            'data_type' => $dataType,
                            'required' => $isRequired,
                            'display_order' => $propertyDef ? $propertyDef['display_order'] : 9999
                        ];

                        Log::info("Berhasil mendapatkan properti {$propId}", [
                            'name' => $metadata[$propId]['name'],
                            'value' => $metadata[$propId]['value']
                        ]);
                    } else {
                        Log::warning("Gagal mendapatkan properti {$propId}: " . $propValueResponse->status());
                    }
                } catch (\Exception $e) {
                    Log::warning("Error saat mengambil properti {$propId}: " . $e->getMessage());
                }
            }
        }

        // Pastikan properti dasar dokumen selalu tersedia
        if (!isset($metadata['1870']) && isset($objectData['Title'])) {
            $metadata['1870'] = [
                'name' => 'Judul',
                'value' => $objectData['Title'],
                'data_type' => 1, // Text
                'required' => true,
                'display_order' => 0
            ];
        }

        if (!isset($metadata['20']) && isset($objectData['Created'])) {
            $metadata['20'] = [
                'name' => 'Tanggal Dibuat',
                'value' => $objectData['Created'],
                'data_type' => 5, // Date
                'required' => true,
                'display_order' => 5
            ];
        }

        if (!isset($metadata['21']) && isset($objectData['LastModified'])) {
            $metadata['21'] = [
                'name' => 'Tanggal Diperbarui',
                'value' => $objectData['LastModified'],
                'data_type' => 5, // Date
                'required' => true,
                'display_order' => 6
            ];
        }

        // Tambahkan ID dokumen sebagai metadata
        $metadata['id'] = [
            'name' => 'ID Dokumen',
            'value' => $objectId,
            'data_type' => 1, // Text
            'required' => true,
            'display_order' => -1
        ];

        // Log metadata yang berhasil dikumpulkan
        Log::info('Metadata berhasil dikumpulkan', [
            'metadata_count' => count($metadata),
            'metadata_keys' => array_keys($metadata)
        ]);

        return response()->json([
            'success' => true,
            'document' => [
                'id' => $objectId,
                'version' => $objectData['ObjVer']['Version'] ?? $version,
                'title' => $objectData['Title'] ?? 'Dokumen Tanpa Judul'
            ],
            'metadata' => $metadata
        ]);

    } catch (\Exception $e) {
        Log::error('Error mengambil metadata dokumen', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
}
