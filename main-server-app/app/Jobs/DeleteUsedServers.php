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
            try {
                // TODO: Need to get the cookie file before deletion
                $vultr->delete_instance($server->server_id);
            } catch (\Throwable $th) {}
            $server->update([
                'destroyed_at' => now(), 'ready' => false, 'status' => 'deleted'
            ]);
        }
    }
}
