<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\StockProduitRepository;
use Illuminate\Http\Request;

class ProductStockController extends Controller
{
    private $stockProduitRepository;

    public  function __construct(StockProduitRepository $stockProduitRepository)
    {
        $this->stockProduitRepository = $stockProduitRepository;
    }

    public function saleStock() {

        $resp = $this->stockProduitRepository->sale_stock();

        return response()->json(['data' => $resp]);
    }

    public function productionStock() {

        $resp = $this->stockProduitRepository->production_stock();

        return response()->json(['data' => $resp]);
    }
}
