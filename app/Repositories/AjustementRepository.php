<?php

namespace App\Repositories;

use App\Models\Produit;
use App\Models\Ajustement;
use App\Models\Conversion;
use App\Models\StockProduit;
use Illuminate\Http\Request;
use App\Models\TypeAjustement;
use App\Models\AjustementProduit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AjustementRepository extends Repository
{
   public function __construct(Ajustement $model)
   {
        $this->model = $model;
   }

}
