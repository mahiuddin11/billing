<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikConnection;
use App\Models\Customer;
use App\Models\MacReseller;
use App\Models\MikrotikServer;
use App\Models\MPool;
use App\Models\User;
use Illuminate\Console\Command;

class MacResellerBilling extends Command
{
    use MikrotikConnection;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'macresellerbill:update';

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
        $macresellers = MacReseller::where('status', 1)->get();
        foreach ($macresellers as $macreseller) {
            $user = User::find($macreseller->user_id);
        }

        return "Mikrotik Poop Update successfully";
    }
}
