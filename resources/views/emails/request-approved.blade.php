<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Dokumen Disetujui</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .info-box {
            background-color: #e9f7ef;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin-top: 15px;
            font-weight: bold;
        }
        h1, h2, h3, h4 {
            color: #28a745;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table td, table th {
            padding: 8px;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin:0;">Permintaan Dokumen Disetujui</h1>
        </div>

        <div class="content">
            <p>Halo {{ $data['user']->name }},</p>

            <p>Kami dengan senang hati memberitahu bahwa permintaan Anda untuk mengakses dokumen telah disetujui.</p>

            <div class="info-box">
                <h3 style="margin-top:0;">Detail Permintaan:</h3>
                <table>
                    <tr>
                        <th>ID Permintaan</th>
                        <td>{{ $data['request']->id }}</td>
                    </tr>
                    <tr>
                        <th>Judul Dokumen</th>
                        <td>{{ $data['request']->document_title }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Dokumen</th>
                        <td>{{ $data['request']->document_number }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Disetujui</th>
                        <td>{{ $data['request']->approved_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>

            @if(isset($data['note']) && !empty($data['note']))
            <div class="info-box">
                <h3 style="margin-top:0;">Catatan dari Admin:</h3>
                <p style="margin-bottom:0;">{{ $data['note'] }}</p>
            </div>
            @endif

            @if(isset($data['expiration']))
            <div class="warning-box">
                <h3 style="margin-top:0; color: #856404;">Penting!</h3>
                <p style="margin-bottom:0;">Akses Anda untuk mengunduh dokumen ini akan berakhir pada <strong>{{ $data['expiration']->format('d M Y, H:i') }}</strong>. Harap unduh dokumen sebelum waktu tersebut.</p>
            </div>
            @endif

            <p>
                @if(isset($filePath) && file_exists($filePath))
                    Dokumen yang Anda minta telah dilampirkan dalam email ini. Anda dapat membukanya langsung dari email.
                @else
                    Untuk mengunduh dokumen, silakan klik tombol di bawah ini:
                    <br><br>
                    <a href="{{ route('stk.download', ['id' => $data['request']->document_id, 'version' => 'latest']) }}" class="button">Unduh Dokumen</a>
                @endif
            </p>

            <p>Jika Anda mengalami masalah dalam mengakses dokumen, silakan hubungi administrator.</p>

            <p>Terima kasih,<br>
            Tim Admin</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon jangan membalas email ini.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Sistem Dokumen') }}. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>
