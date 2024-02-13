<?php

namespace App\Repositories;

use App\Models\Stock_produit;
use App\Repositories\Repository;

class StockProduitRepository extends Repository
{

    
    public function __construct(Stock_produit $model)
    {
        $this->model = $model;
    }

}
