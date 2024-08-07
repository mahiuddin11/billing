<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\MikrotikServer;
use App\Models\MPool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MikPoopSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'poopsync:update';

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
                $pooplists = $client->q('/ip/pool/print')->r();
                foreach ($pooplists as $pooplist) {
                    $mpppl = MPool::where('mid', $pooplist['.id'])->where('server_id', $server->id)->first();
                    if (!$mpppl) {
                        MPool::create(
                            [
                                'mid' => $pooplist['.id'],
                                'name' => $pooplist['name'],
                                'server_id' => $server->id,
                                'ranges' => $pooplist['ranges'],
                            ]
                        );
                    }
                }
            }
            return "Mikrotik Poop Update successfully";
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }

        $servers = MikrotikServer::where('status', true)->get();
        foreach ($servers as $server) {
            $client = $this->client($server->id);
            $pooplists = $client->q('/ip/pool/print')->r();
            foreach ($pooplists as $pooplist) {
                $mpppl = MPool::where('mid', $pooplist['.id'])->where('server_id', $server->id)->first();
                if (!$mpppl) {
                    MPool::create(
                        [
                            'mid' => $pooplist['.id'],
                            'name' => $pooplist['name'],
                            'server_id' => $server->id,
                            'ranges' => $pooplist['ranges'],
                        ]
                    );
                }
            }
        }
        return "Mikrotik Poop Update successfully";
    }
}
