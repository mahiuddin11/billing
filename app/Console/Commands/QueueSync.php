<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\IpAddress;
use App\Models\MikrotikServer;
use App\Models\MPool;
use App\Models\Queue;
use App\Models\Vlan;
use Illuminate\Console\Command;

class QueueSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queueSync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue sync By mikrotik';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $servers = MikrotikServer::where('status', true)->get();
        foreach ($servers as $servers) {
            $client = $this->client($servers->id);
            $queues = $client->q('/queue/simple/print')->r();
            foreach ($queues as $vlan) {
                $uploadlimit = explode('/', $vlan['max-limit']);
                $upload =  1000000 <= $uploadlimit[0] ? ($uploadlimit[0] / 1000) / 1000 . "M" : ($uploadlimit[0] / 1000) . "K";
                $download =  1000000 <= $uploadlimit[1] ? ($uploadlimit[0] / 1000) / 1000 . "M" : ($uploadlimit[0] / 1000) . "K";
                $queue = Queue::where('queue_mid', $vlan['.id'])->where('server_id', $servers->id)->first();
                if (!$queue) {
                    Queue::create(
                        [
                            'queue_mid' => $vlan['.id'],
                            'server_id' => $servers->id,
                            'queue_name' => $vlan['name'] ?? null,
                            'queue_max_upload' => $upload,
                            'queue_max_download' => $download,
                            'queue_target' => $vlan['target'] ?? null,
                            'queue_dst' => $vlan['dst'] ?? null,
                            'queue_disabled' => $vlan['disabled'] ?? null,
                        ]
                    );
                }
            }
        }
        return "Vlan Update successfully";
    }
}
