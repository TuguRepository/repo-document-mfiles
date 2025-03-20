<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Dokumen Ditolak</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #d9534f;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .document-box {
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background-color: #5bc0de;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #46b8da;
        }
        .reason {
            background-color: #ffeeee;
            border-left: 4px solid #d9534f;
            padding: 10px 15px;
            margin: 20px 0;
        }
        .note {
            background-color: #fffceb;
            border-left: 4px solid #ffcc00;
            padding: 10px 15px;
            margin: 20px 0;
        }
        .alternative {
            background-color: #efffef;
            border-left: 4px solid #5cb85c;
            padding: 10px 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Permintaan Dokumen Ditolak</h1>
        </div>

        <div class="content">
            <p>Halo {{ $data['user']['name'] }},</p>

            <p>Kami ingin menginformasikan bahwa permintaan Anda untuk mengunduh dokumen telah <strong>ditolak</strong>.</p>

            <div class="document-box">
                <h3>Informasi Dokumen</h3>
                <p><strong>Judul:</strong> {{ $data['request']->document_title }}</p>
                @if(isset($data['request']->document_number))
                <p><strong>Nomor:</strong> {{ $data['request']->document_number }}</p>
                @endif
                @if(isset($data['request']->document_version))
                <p><strong>Versi:</strong> {{ $data['request']->document_version }}</p>
                @endif
                <p><strong>Status Permintaan:</strong> <span style="color: red;">Ditolak</span></p>
                <p><strong>Tanggal Penolakan:</strong> {{ $data['request']->rejected_at->format('d M Y H:i') }}</p>
            </div>

            <div class="reason">
                <h3>Alasan Penolakan</h3>
                <p>{{ $data['rejection_reason'] }}</p>
            </div>

            @if(isset($data['note']) && $data['note'])
            <div class="note">
                <h3>Catatan Admin</h3>
                <p>{{ $data['note'] }}</p>
            </div>
            @endif

            @if(isset($data['alternative_document']))
            <div class="alternative">
                <h3>Dokumen Alternatif</h3>
                <p>Admin menyarankan dokumen alternatif berikut yang mungkin sesuai dengan kebutuhan Anda:</p>
                <p><strong>Judul:</strong> {{ $data['alternative_document']->title }}</p>
                @if(isset($data['alternative_document']->number))
                <p><strong>Nomor:</strong> {{ $data['alternative_document']->number }}</p>
                @endif
                <p>Untuk mengakses dokumen ini, silakan buat permintaan baru.</p>
            </div>
            @endif

            <p>Anda dapat melihat detail permintaan Anda dan mengajukan permintaan baru dengan menekan tombol di bawah ini:</p>

            <div style="text-align: center;">
                <a href="{{ $data['detail_url'] }}" class="button">Lihat Detail Permintaan</a>
            </div>

            <p>Jika Anda memiliki pertanyaan atau membutuhkan klarifikasi lebih lanjut, silakan hubungi administrator dokumen.</p>

            <p>Terima kasih,<br>Tim Document Management</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Document Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
