<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class DocumentsList extends Component
{
    public $documents = [];

    public function mount()
    {
        $this->fetchDocuments();
    }

    private function getAuthToken()
    {
        // Ambil token menggunakan metode yang sudah ada
        try {
            $authResponse = Http::post('https://mf.tugu.com/REST/session.aspx');

            if ($authResponse->successful()) {
                return $authResponse->body(); // Token dikembalikan sebagai string
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    private function fetchDocuments()
    {
        $authToken = $this->getAuthToken();

        if (!$authToken) {
            session()->flash('error', 'Gagal mendapatkan token autentikasi');
            return;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'X-Authentication' => $authToken,
        ])->get('https://mf.tugu.com/REST/objects.aspx?p100=153');

        if ($response->successful()) {
            $data = $response->json();
            $this->documents = $data['Items'] ?? [];
        } else {
            session()->flash('error', 'Gagal mengambil data dokumen');
            $this->documents = [];
        }
    }

    public function render()
    {
        return view('livewire.documents-list');
    }
}
