<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
use App\Models\Affiliates;
// use Carbon\Carbon;


class TrackdeskService
{

    private $limit = 100;
    private $api = '';
    private $headers = [];
    public function __construct()
    {
        $this->api = "https://".$this->getTenantId().".trackdesk.com"; 
        $this->headers = ['x-api-key' => $this->getPrivateKey()];
    }

    private function getTenantId(): string 
    {
        return trim(getenv('TRACKDESK_TENANTID'));
    }
    private function getPrivateKey(): string
    {
        return trim(getenv('TRACKDESK_API_KEY'));
    }
    public function createAffiliate($post): mixed 
    {
        return Http::withHeaders($this->headers)->acceptJson()->post($this->api . '/api/node/affiliates/v1/register-with-user', $post)->json();
    }
    public function isValidRequest($data): bool 
    {
        return (isset($data['tenantId']) && str_contains($this->api, $data['tenantId'])) ? true : false; 
    }
    public function saveAffiliate($data) {
        $type = isset($data['affiliateCreated']) ? 'affiliateCreated' : 'affiliateUpdated';
        $affiliate = $data["affiliateCreated"];
        Affiliates::updateOrCreate(
            // Find
            [
                'email' => $affiliate["email"],
            ],
            // Add or Update
            [
                'tenantId' => $data["tenantId"],
                'type' => $type,
                'registeredAt' => date ('Y-m-d H:i:s',strtotime($affiliate["registeredAt"])),
                'publicId' => $affiliate["publicId"],
                'email' => $affiliate["email"],
                'name' => $affiliate["name"],
                'status' => $affiliate["status"],
                'tierId' => $affiliate["tierId"],
                'tierName' => $affiliate["tierName"],
                'accountId' => $affiliate["accountId"],
                'sourceId' => $affiliate["sourceId"],
                'sourceStatus' => $affiliate["sourceStatus"],
                'fraudSuspicion' => $affiliate["fraudSuspicion"],
            ]
        );
    }
}
