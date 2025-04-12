<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
// use Carbon\Carbon;


class FirstpromoterService
{
    private $api = '';
    private $headers = [];
    public function __construct()
    {
        $this->api = "https://firstpromoter.com/api/v1"; 
        $this->headers = ['x-api-key' => $this->getPrivateKey()];
    }
    private function getPrivateKey(): string
    {
        return trim(getenv('FIRSTPROMOTER_API_KEY'));
    }
    public function showPromoter($email) {
        return Http::withHeaders($this->headers)->acceptJson()->get($this->api . '/promoters/show?promoter_email='. $email)->json();
    }
}
