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
    function get_instance($id) 
    {
        return $this->client->get('instances/' . $id)->json();    
    }
    function delete_instance($id) 
    {
        return $this->client->delete('instances/' . $id)->json();    
    }
    function create_instance()
    {
        return $this->client->post('instances', [
            'region' => 'lax',
            'plan' => 'vc2-1c-2gb',
            'os_id' => 1743, // Ubuntu 22.04
            'script_id' => 'f4294c24-50e5-4c76-8405-3b4744461a3f',
            'backups' => 'disabled'
        ])->json();
    } 
    function test()
    {
        return $this->client->get('os')->json();
    }
}