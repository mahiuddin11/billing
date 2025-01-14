<?php

namespace App\Console\Commands;

use App\Models\Navigation;
use App\Models\RollPermission;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class NavRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nav:fresh';

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
        Navigation::truncate();
        RollPermission::truncate();
        Artisan::call("db:seed NavigationSeeder");
    }
}
