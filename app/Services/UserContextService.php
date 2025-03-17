<?php

namespace App\Services;

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\EmployeeController;

class UserContextService
{

    private $payload;
    private $roleData;
    private $employeeController;
    private $accessToken;

    public function __construct(EmployeeController $employeeController)
    {
        $this->employeeController = $employeeController;
        $this->initializePayload();
    }

    private function initializePayload()
    {
        try {
            $refresh_token = $_COOKIE['refresh_token'] ?? null;

            if ($refresh_token) {
                $decoded = JWT::decode(
                    $refresh_token,
                    new Key(env('JWT_REFRESH_TOKEN'), 'HS256')
                );

                $this->payload = (array) $decoded;
                $this->fetchRoleData();
                $this->makeAccessToken();
            }
        } catch (\Exception $e) {
            $this->payload = null;
        }
    }

    private function fetchRoleData()
    {
        try {
            $email = $this->payload['email'];
            if ($email) {
                $response = $this->employeeController->getRole($email);

                if ($response->getStatusCode() === 200) {
                    $this->roleData = $response->getContent();
                }
            }
        } catch (\Exception $e) {
            $this->roleData = null;
        }
    }

    private function makeAccessToken()
    {
        try {
            if (!$this->payload || !isset($this->payload['exp']) || $this->payload['exp'] < time()) {
                $this->accessToken = null;
            }

            $newPayload = [
                'email' => $this->payload['email'] ?? null,
                'partner' => $this->payload['partner'] ?? null,
                'cost_center' => $this->payload['cost_center'] ?? null,
                'name' => $this->payload['name'] ?? null,
                'job_title' => $this->payload['job_title'] ?? null,
                'listApplication' => $this->payload['listApplication'] ?? [],
            ];

            $expirationMinutes = env('JWT_ACCESS_TOKEN', 15);

            $newPayload['iat'] = time();
            $newPayload['exp'] = time() + $expirationMinutes * 60;

            $jwtProvider = app('tymon.jwt.provider.jwt');

            Log::error("WKWKWKWKWK".$jwtProvider->encode($newPayload));
            $this->accessToken = $jwtProvider->encode($newPayload);

        } catch (\Exception $e) {
            Log::error('Error creating access token: ' . $e->getMessage());
            $this->accessToken = null;
        }
    }

    public function getPayload()
    {
        return $this->payload;
    }

    public function getRoleData()
    {
        return $this->roleData;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getAllContext()
    {
        return [
            'payload' => $this->payload,
            'roleData' => $this->roleData
        ];
    }
}
