<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller
{
    protected $sessionUrl = 'https://mf.tugu.com/REST/session.aspx?_method=PUT';
    protected $authUrl = 'https://mf.tugu.com/REST/server/authenticationtokens';
    
/**
 * Display a listing of the documents.
 *
 * @return \Illuminate\Http\Response
 */
public function index()
{
    return $this->fetchDocuments();
}
    /**
     * Get authentication token from M-Files
     */
    public function getAuthToken()
    {
        try {
            // Get credentials from .env or config (NEVER hardcode)
            $credentials = [
                'Username' => config('mfiles.username'),
                'Password' => config('mfiles.password'),
                'VaultGuid' => config('mfiles.vault_guid')
            ];

            // Create session
            $sessionResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->put($this->sessionUrl, $credentials);

            if (!$sessionResponse->successful()) {
                Log::error('M-Files session creation failed', [
                    'status' => $sessionResponse->status(),
                    'response' => $sessionResponse->body()
                ]);

                return null; // Mengembalikan null jika gagal
            }

            $sessionData = $sessionResponse->json();

            // Get token
            $tokenResponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'X-Authentication' => $sessionData['Authentication'] ?? '',
                'X-SessionKey' => $sessionData['SessionID'] ?? '',
            ])->post($this->authUrl, $credentials);

            if (!$tokenResponse->successful()) {
                Log::error('M-Files token retrieval failed', [
                    'status' => $tokenResponse->status(),
                    'response' => $tokenResponse->body()
                ]);

                return null; // Mengembalikan null jika gagal
            }

            $tokenData = $tokenResponse->json();
            return $tokenData['Value'] ?? null; // Mengembalikan token
        } catch (\Exception $e) {
            Log::error('M-Files authentication error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return null;
        }
    }

    /**
     * Fetch documents from M-Files API
     */
        /**
     * Fetch documents from M-Files API
     */
    public function fetchDocuments()
    {
        try {
            $authToken = $this->getAuthToken(); // Ambil token autentikasi

            if (!$authToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan token autentikasi'
                ], 401);
            }

            // Ambil data dokumen dari API M-Files
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Authentication' => $authToken,
            ])->get('https://mf.tugu.com/REST/objects.aspx?p100=153');

            if ($response->successful()) {
                $data = $response->json();
                $documents = $data['Items'] ?? [];

                return view('documents-list', compact('documents'));
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dokumen',
                    'status' => $response->status(),
                    'response' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('M-Files fetchDocuments error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
       }
}
