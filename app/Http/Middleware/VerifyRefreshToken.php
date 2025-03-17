<?php

namespace App\Http\Middleware;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class VerifyRefreshToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $refreshToken = $request->cookie('refresh_token') ??
                    Cookie::get('refresh_token') ??
                    $request->cookies->get('refresh_token') ??
                    $_COOKIE['refresh_token'] ?? null;

        $refreshToken =  $_COOKIE['refresh_token'] ?? null;

        Log::info("Testing refresh token" . $refreshToken);

        if (!$refreshToken) {
            $originalUrl = $request->fullUrl();
            $encodedUrl = base64_encode($originalUrl);

            $loginUrl = env('APP_URL_SSO') . '/login';
            $redirectUrl = $loginUrl . '?redirect=' . $encodedUrl;

            return redirect($redirectUrl)->header('X-Original-URL', $originalUrl);
        }

        try {
            $decoded = JWT::decode(
                $refreshToken,
                new Key(env('JWT_REFRESH_TOKEN', 'pqvFK4ApAaWGkRdgPR1ZAUepwp1Z4TL6jpM9ZyNL0RCe90DHaZ8UnXYG8q12'), 'HS256')
            );

            $payload = (array) $decoded;

            if (!$payload) {
                Log::error('Payload not found in token');
                return redirect('/accessdenied');
            }

            $listApplication = isset($payload['listApplication']) ? (array) $payload['listApplication'] : [];
            $hasAccess = in_array(env('APP_NAME'), $listApplication);


            if (!$hasAccess) {
                Log::error('Application not found in listApplication', [
                    'app_name' => env('APP_NAME'),
                    'available_apps' => $listApplication
                ]);
                return redirect('/accessdenied');
            }

            $response = $next($request);

            $response->headers->set('X-User-Payload', json_encode($payload));

            return $response;

        } catch (\Exception $e) {
            Log::error('Token verification failed', [
                'error' => $e->getMessage(),
                'token' => $refreshToken,
            ]);

            return redirect(env('APP_URL_SSO') . '/login');
        }
    }
    protected $except = [
        'api/*',
        '/token',
        '/logout'
    ];
}
