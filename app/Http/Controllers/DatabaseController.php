<?php

namespace App\Http\Controllers;

use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Facades\UserContext;
use Illuminate\Support\Facades\Http;

class DatabaseController extends Controller
{
    public function dashboard()
    {
        return redirect('/home');
    }

    public function index(Request $request)
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

        return view('stk.database', [
            'namaUser' => $payload['name'],
            'jobTitle' => $payload['job_title'],
            'imageData' => $imageData,
        ]);

    }
}
