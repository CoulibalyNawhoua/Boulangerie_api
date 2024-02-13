<?php

namespace App\Repositories;


use App\Models\Stock_produit;
use App\Models\Inventaire;
use App\Repositories\Repository;
use App\Models\Inventaire_has_produit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventaireRepository extends Repository
{
    public function __construct(Inventaire $model)
    {
        $this->model = $model;
    }

}
