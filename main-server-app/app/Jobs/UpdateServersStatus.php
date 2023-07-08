<?php

namespace App\Jobs;

use App\Models\Server;
use App\Services\VultrApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateServersStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $vultr = new VultrApi;

        Server::query()
            ->whereNull('destroyed_at')
            ->chunk(100, function($servers) use($vultr) {
            foreach($servers as $server)
            {
                $get = $vultr->get_instance($server->server_id);
                if($get && isset($get['instance']))
                {
                    $server->update([
                        'status' => $get['instance']['status'],
                        'server_ip' => $get['instance']['main_ip']
                    ]);
                    if($get['instance']['status'] === 'active')
                    {
                        $ip = $server->server_ip;
                        // check vnc link 
                        if(
                            Http::timeout(2)
                                ->get(get_vnc_url($ip))
                                ->successful()
                        )
                        {
                            if(!$server->ready)
                            {
                                // run any startup script 
                            }
                            $server->update(['ready' => true]);
                        }else 
                        {
                            $server->update(['ready' => false]);
                        }
                    }
                }
                else 
                {
                    $server->update([
                        'ready' => false,
                        'status' => 'deleted'
                    ]);
                }
            }
        });
    }
}
