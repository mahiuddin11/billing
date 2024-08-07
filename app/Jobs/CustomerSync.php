<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\MikrotikQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSync implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        MikrotikQuery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $companyId;
    protected $keyword;
    protected $serverModel;
    public function __construct($request, $serverModel)
    {
        $this->companyId = $request->company_id;
        $this->keyword = $request->keyword;
        $this->serverModel = $serverModel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
    }
}
