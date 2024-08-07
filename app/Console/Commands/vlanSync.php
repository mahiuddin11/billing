<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\MikrotikServer;
use App\Models\MPool;
use App\Models\Vlan;
use Illuminate\Console\Command;

class vlanSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vlanSync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'vlanSync By mikrotik';

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
                $vlanlist = $client->q('/interface/vlan/print')->r();
                foreach ($vlanlist as $vlan) {
                    $vlancheck = Vlan::where('mid', $vlan['.id'])->where('server_id', $server->id)->first();
                    if (!$vlancheck) {
                        Vlan::create(
                            [
                                'mid' => $vlan['.id'],
                                'server_id' => $server->id,
                                'name' => $vlan['name'],
                                'mtu' => $vlan['mtu'],
                                'disabled' => $vlan['disabled'],
                                'arp' => $vlan['arp'],
                                'vlan_id' => $vlan['vlan-id'],
                                'interface' => $vlan['interface'],
                                'use_service_tag' => $vlan['use-service-tag'],
                            ]
                        );
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($th->getMessage() . $th->getFile() . $th->getLine());
        }
        return "Vlan Update successfully";
    }
}
