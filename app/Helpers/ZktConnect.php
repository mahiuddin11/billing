<?php

namespace App\Helpers;

use Rats\Zkteco\Lib\ZKTeco;

trait ZktConnect
{
    protected $ip;
    protected $accress = "192.168.0.142";

    public function __construct() {
        $this->ip = new ZKTeco($this->accress);
    }

    public function zktConnect()
    {
        $zkteco = $this->ip;
        $zkteco = $zkteco->connect();
        return $zkteco;
    }

    public function useZkt()
    {
        $usezkt = $this->ip;
        return $usezkt;
    }
}
