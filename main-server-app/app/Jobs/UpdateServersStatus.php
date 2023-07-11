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
use phpseclib3\Net\SSH2;

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
                    if($get['instance']['status'] === 'active' && !$server->ready)
                    {
                        $ip = $server->server_ip;
                        // check if setup is done 
                        if(
                            Http::timeout(2)->get('http://' . $ip)
                        )
                        {
                            sleep(2);
                            $ssh = new SSH2($ip, 22);
                            if (!$ssh->login('root', 'whattheFuxk1231')) {
                                throw new \Exception('Connection failed');
                            }
                            $ssh->exec('vncserver');
                            $ssh->exec("nohup ./private-cloud-browser-project/novnc/utils/novnc_proxy --vnc localhost:5901 --listen 81 &");
                            $ssh->disconnect();
                            sleep(2);
                            if(
                                Http::timeout(2)
                                    ->get(get_vnc_url($ip))
                                    ->successful()
                            )
                            {
                                $server->update(['ready' => true]);
                            }else 
                            {
                                $server->update(['ready' => false]);
                            }
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
