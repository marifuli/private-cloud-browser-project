<?php 
namespace App\Services;

use Illuminate\Support\Facades\Http;

class VultrApi 
{
    public $client;
    public function __construct() {
        $this->client = Http::baseUrl("https://api.vultr.com/v2/")
            ->withHeader('Authorization', "Bearer " . env('VULTR_API_KEY'));
    }
    function get_instances() 
    {
        return $this->client->get('instances')->json();    
    }
}