<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\MikrotikServer;
use App\Models\MPool;
use App\Models\MPPPProfile;
use App\Models\VlanInterface;
use Illuminate\Console\Command;

class VlanInterfaceSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'vlaninterfaceSync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {


            $servers = MikrotikServer::where('status', true)->get();
            foreach ($servers as $server) {
                $client = $this->client($server->id);
                $mikrotik = $client->q('/interface/print')->r();
                foreach ($mikrotik as $interface) {
                    $vlaninterface = VlanInterface::where('mid', $interface['.id'])->where('server_id', $server->id)->first();
                    if (!$vlaninterface) {
                        VlanInterface::create(
                            [
                                'mid' => $interface['.id'],
                                'server_id' => $server->id ?? null,
                                'name' => $interface['name'],
                                'type' => $interface['type'],
                                'mtu' => $interface['mtu'] ?? null,
                                'actual_mtu' => $interface['actual-mtu'] ?? null,
                                'running' => $interface['running'] ?? null,
                                'disabled' => $interface['disabled']  ?? null,
                            ]
                        );
                    }
                }
            }
            return "Mikrotik Profile Update successfully";
        } catch (\Throwable $th) {
            dd($th->getMessage() . $th->getFile() . $th->getLine());
        }
    }
}
