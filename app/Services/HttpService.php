<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Utils;

class HttpService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout'  => 10.0, // Timeout 10 detik
        ]);
    }

    public function sendRequest($method, $url, $data = [], $headers = [])
    {
        try {
            $options = [
                'headers' => $headers,
            ];

            if ($method === 'GET') {
                $options['query'] = $data;
            } else {
                $options['json'] = $data;
            }

            $response = $this->client->request($method, $url, $options);
            $contentType = strtolower($response->getHeaderLine('Content-Type'));

            // Coba baca beberapa byte pertama
            $stream = $response->getBody();
            $peek = $stream->read(4);
            $stream->rewind();

            if (strpos($contentType, 'application/json') !== false) {
                $json = json_decode($stream->getContents(), true);
                return json_last_error() === JSON_ERROR_NONE ? $json : $response;
            } elseif ($peek === "PK\x03\x04" || strncmp($peek, "%PDF", 4) === 0) {
                // Jika ZIP/PDF, return ResponseInterface agar bisa pakai getBody() nantinya
                return $response;
            } else {
                return $response;
            }
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $responseBody = (string) $e->getResponse()->getBody();
                $decodedJson = json_decode($responseBody, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'error' => true,
                        'status' => $decodedJson['status'] ?? null,
                        'message' => $decodedJson['message'] ?? 'Terjadi kesalahan',
                    ];
                }
            }

            return [
                'error' => true,
                'status' => null,
                'message' => $e->getMessage(),
            ];
        }
  }
}
