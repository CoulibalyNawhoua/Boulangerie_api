<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BakehouseRepository extends Controller
{
    private $bakehouseRepository;

    public function __construct(BakehouseRepository $bakehouseRepository)
    {
        $this->bakehouseRepository = $bakehouseRepository;
    }
}
