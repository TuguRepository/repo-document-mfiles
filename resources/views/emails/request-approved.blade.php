<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Dokumen Disetujui</title>
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
            background-color: #2176bd;
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
            background-color: #2176bd;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #1a5b8e;
        }
        .note {
            background-color: #fffceb;
            border-left: 4px solid #ffcc00;
            padding: 10px 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
        .expiration {
            background-color: #ffeeee;
            border-left: 4px solid #ff6666;
            padding: 10px 15px;
            margin: 20px 0;
            font-weight: bold;
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
            <h1>Permintaan Dokumen Disetujui</h1>
        </div>

        <div class="content">
            <p>Halo {{ $data['user']['name'] }},</p>

            <p>Permintaan Anda untuk mengunduh dokumen telah <strong>disetujui</strong>.</p>

            <div class="document-box">
                <h3>Informasi Dokumen</h3>
                <p><strong>Judul:</strong> {{ $data['request']->document_title }}</p>
                @if(isset($data['request']->document_number))
                <p><strong>Nomor:</strong> {{ $data['request']->document_number }}</p>
                @endif
                @if(isset($data['request']->document_version))
                <p><strong>Versi:</strong> {{ $data['request']->document_version }}</p>
                @endif
                <p><strong>Status Permintaan:</strong> <span style="color: green;">Disetujui</span></p>
                <p><strong>Tanggal Persetujuan:</strong> {{ $data['request']->approved_at->format('d M Y H:i') }}</p>
                @if(isset($data['note']) && $data['note'])
                <div class="note">
                    <p><strong>Catatan:</strong> {{ $data['note'] }}</p>
                </div>
                @endif
            </div>

            <p>Anda dapat mengunduh dokumen dengan menekan tombol di bawah ini:</p>

            <div style="text-align: center;">
                <a href="{{ $data['download_url'] }}" class="button">Unduh Dokumen</a>
            </div>

            @if(isset($data['expiration']) && $data['expiration'])
            <div class="expiration">
                <p>⚠️ Perhatian: Link download ini hanya berlaku hingga {{ $data['expiration']->format('d M Y H:i') }}</p>
            </div>
            @endif

            <p>Jika Anda memiliki pertanyaan, silakan hubungi administrator dokumen.</p>

            <p>Terima kasih,<br>Tim Document Management</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon untuk tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Document Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
