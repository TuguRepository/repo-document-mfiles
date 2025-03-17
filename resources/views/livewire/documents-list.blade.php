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
    </style>
</head>
<body>
    <div class="container mt-4 mb-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0">Daftar Dokumen STK</h4>
                <div>
                    <a href="{{ url('/documents') }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </a>
                </div>
            </div>
            <div class="card-body">
                <pre>{{ json_encode($documents, JSON_PRETTY_PRINT) }}</pre>

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
                                    <th style="width: 40%">Judul Dokumen</th>
                                    <th style="width: 25%">DisplayID</th>
                                    <th style="width: 15%">Object Type</th>
                                    <th style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $index => $document)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $document['Title'] ?? 'Tanpa Judul' }}</td>
                                        <td>{{ $document['DisplayID'] ?? '-' }}</td>
                                        <td>{{ $document['ObjectType'] ?? '-' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm" 
                                               onclick="viewDocument('{{ $document['ObjVer']['ID'] ?? 0 }}')">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to view document details
        function viewDocument(id) {
            // You can implement the view functionality here
            alert('Melihat dokumen dengan ID: ' + id);
        }
    </script>
</body>
</html>