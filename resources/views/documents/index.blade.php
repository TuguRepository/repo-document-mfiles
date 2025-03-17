<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Dokumen STK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .table th {
            background-color: #f1f1f1;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .action-buttons .btn {
            margin-right: 5px;
        }
        .badge-approved {
            background-color: #198754;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-rejected {
            background-color: #dc3545;
        }
        .badge-draft {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0">Daftar Dokumen STK</h4>
                <div>
                    <button class="btn btn-light btn-sm me-2">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Dokumen
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and filter -->
                <div class="row mb-4">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari dokumen...">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select class="form-select">
                            <option value="">Semua Status</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select">
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                            <option value="50">50 per halaman</option>
                        </select>
                    </div>
                </div>

                @if(empty($documents))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada dokumen yang ditemukan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 35%">Judul Dokumen</th>
                                    <th style="width: 15%">Nomor Dokumen</th>
                                    <th style="width: 15%">Status</th>
                                    <th style="width: 15%">Tanggal Perubahan</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $index => $document)
                                    @php
                                        // Extract document properties
                                        $title = $document['Title'] ?? 'Tanpa Judul';
                                        
                                        // Find property with PropertyDef = 39 (status)
                                        $status = 'Draft';
                                        $statusClass = 'badge-draft';
                                        
                                        // Find document number (assuming it's in properties)
                                        $documentNumber = '-';
                                        
                                        // Process properties if they exist
                                        if (isset($document['Properties'])) {
                                            foreach ($document['Properties'] as $property) {
                                                if (isset($property['PropertyDef']) && $property['PropertyDef'] == 39) {
                                                    $status = $property['DisplayValue'] ?? 'Draft';
                                                    // Set the badge color based on status
                                                    if (stripos($status, 'approved') !== false) {
                                                        $statusClass = 'badge-approved';
                                                    } elseif (stripos($status, 'pending') !== false || stripos($status, 'assigne') !== false) {
                                                        $statusClass = 'badge-pending';
                                                    } elseif (stripos($status, 'reject') !== false) {
                                                        $statusClass = 'badge-rejected';
                                                    }
                                                }
                                                
                                                // Try to find document number (assuming PropertyDef = 1853 for doc number based on your previous data)
                                                if (isset($property['PropertyDef']) && $property['PropertyDef'] == 1853) {
                                                    $documentNumber = $property['DisplayValue'] ?? '-';
                                                }
                                            }
                                        }
                                        
                                        // Format the last modified date
                                        $lastModified = isset($document['LastModified']) 
                                            ? date('d/m/Y H:i', strtotime($document['LastModified'])) 
                                            : '-';
                                    @endphp
                                    
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $title }}</td>
                                        <td>{{ $documentNumber }}</td>
                                        <td>
                                            <span class="badge status-badge {{ $statusClass }}">{{ $status }}</span>
                                        </td>
                                        <td>{{ $lastModified }}</td>
                                        <td class="action-buttons">
                                            <a href="#" class="btn btn-primary btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="#" class="btn btn-success btn-sm" title="Download">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <a href="#" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // You can add any JavaScript functionality here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Add click event listener to refresh button
            const refreshBtn = document.querySelector('.btn-light');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', function() {
                    location.reload();
                });
            }
            
            // Add more interactivity as needed
        });
    </script>
</body>
</html>