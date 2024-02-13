<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\ProductUnit;
use Carbon\Carbon;
use App\Models\Reception;
use App\Models\Procurement;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use App\Models\ReceptionProcuct;
use App\Repositories\Repository;
use App\Models\ProcurementProduct;
use App\Models\Produit;
use Illuminate\Support\Facades\Auth;



class ReceptionProduitRepository extends Repository
{
    public function __construct(Reception $model)
    {
        $this->model = $model;
    }

}
