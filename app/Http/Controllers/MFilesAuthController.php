<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MFilesAuthController extends Controller
{
    protected $sessionUrl = 'https://mf.tugu.com/REST/session.aspx?_method=PUT';
    protected $authUrl = 'https://mf.tugu.com/REST/server/authenticationtokens';
    protected $fixedVaultGuid = '5D8FF911-CE06-4B27-8311-B0AD764921C0';

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

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat sesi M-Files'
                ], 500);
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

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan token autentikasi'
                ], 500);
            }

            $tokenData = $tokenResponse->json();

            // Return only the token to the frontend
            return response()->json([
                'success' => true,
                'token' => $tokenData['Value'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('M-Files authentication error', [
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
     * Get authentication token using user-provided credentials
     * Always uses the fixed vault GUID
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            Log::info("TESTING 1");
            // Validate input
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            // Get credentials from request input only
            $credentials = [
                'Username' => $validated['username'],
                'Password' => $validated['password'],
                'VaultGuid' => '{5D8FF911-CE06-4B27-8311-B0AD764921C0}'
            ];

            // Create session
            $sessionResponse = Http::withHeaders([
                'Accept' => 'application/json'
            ])->put($this->sessionUrl, $credentials);

            if (!$sessionResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username atau Password salah'
                ], 401);
            }

            $sessionData = $sessionResponse->json();

                // Get token
            $tokenResponse = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($this->authUrl, $credentials);

            if (!$tokenResponse->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mendapatkan token autentikasi'
                ], 500);
            }


            $tokenData = $tokenResponse->json();
            $token = $tokenData['Value'] ?? null;

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak ditemukan'
                ], 500);
            }


            // Return to frontend
            return response()->json([
                'success' => true,
                'token' => $token,
                'username' => $validated['username']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'username' => $validated['username']
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Clear session
        $request->session()->forget('user_authenticated');
        $request->session()->forget('mfiles_token');
        $request->session()->forget('username');

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Verify if a token is still valid
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyToken(Request $request)
    {
        try {
            $token = $request->header('X-Authentication');

            if (!$token) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Token tidak disediakan'
                ]);
            }

            // Simple verification - just check the connection to a protected endpoint
            $response = Http::withHeaders([
                'X-Authentication' => $token,
                'Accept' => 'application/json'
            ])->get('https://mf.tugu.com/REST/structure/classes');

            return response()->json([
                'valid' => $response->successful(),
                'status' => $response->status()
            ]);

        } catch (\Exception $e) {
            Log::error('Token verification error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'valid' => false,
                'message' => 'Error saat verifikasi token: ' . $e->getMessage()
            ]);
        }
    }


}
