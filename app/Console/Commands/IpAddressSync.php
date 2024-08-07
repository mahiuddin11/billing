<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\IpAddress;
use App\Models\MikrotikServer;
use App\Models\MPool;
use App\Models\Vlan;
use Illuminate\Console\Command;

class IpAddressSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipaddresssync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ip address sync By mikrotik';

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
        foreach ($servers as $server) {
            $client = $this->client($server->id);
            $vlanlist = $client->q('/ip/address/print')->r();
            foreach ($vlanlist as $vlan) {
                $ipAddress = IpAddress::where('mid', $vlan['.id'])->where('server_id', $server->id)->first();
                if (!$ipAddress) {
                    IpAddress::updateOrCreate(
                        [
                            'mid' => $vlan['.id'],
                            'address' => $vlan['address'],
                            'server_id' => $server->id,
                            'network' => $vlan['network'],
                            'disabled' => $vlan['disabled'],
                            'interface' => $vlan['interface'],
                        ]
                    );
                }
            }
        }
        return "Vlan Update successfully";
    }
}
