<?php

namespace App\Jobs;

use App\Http\Controllers\HomeController;
use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CheckAndCreateMinimumLiveServers implements ShouldQueue
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
        $required = (int) Cache::get('min_server') ?? 0;
        $servers = Server::query()->whereNull('destroyed_at')->get();
        $count = $servers->count();
        if($required == 0 || $required > 10)
        {
            return ;
        }
        if($count < $required)
        {
            $needed = $required - $count + 1;
            for ($i=0; $i < $needed; $i++) 
            { 
                (new HomeController)->create();
            }
        }else 
        if($count > ($required + 5))
        {
            $too_much = $count - ($required + 5);
            $raw_servers = $servers->toArray();
            for ($i=0; $i < $too_much; $i++) 
            { 
                $ser = $raw_servers[$i] ?? null;
                if($ser)
                {
                    (new HomeController)->delete($ser['server_id']);
                }
            }
        }
    }
}
