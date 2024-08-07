<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\MikrotikServer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CustomerSync extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customerstatussync:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CUstomer Sync';

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
            $users = $client->q('/ppp/secret/print')->r();
            foreach ($users as $user) {
                $customer[] =   [
                    "mid" => isset($user['.id']) ? $user['.id'] : null,
                    "username" => isset($user['name']) ? $user['name'] : null,
                    "disabled" => isset($user['disabled']) ? $user['disabled'] : null,
                ];
            }
            DB::table('customers')->upsert($customer, ['mid', 'username'], ['disabled']);
        }
        return back()->with('success', 'We are Working On Sync Data');
    }
}
