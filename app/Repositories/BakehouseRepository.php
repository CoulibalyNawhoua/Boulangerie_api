<?php

namespace App\Repositories;

use App\Models\Bakehouse;
use App\Models\Customer;
use App\Models\Delivery;
use App\Models\DeliveryDetails;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderReturn;
use App\Models\OrderReturnDetail;
use App\Models\Procurement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockProduct;
use App\Models\Supplier;
use App\Models\TechnicalSheet;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BakehouseRepository extends Repository
{
    public function __construct(Bakehouse $model)
    {
        $this->model = $model;
    }

    public function dashboard_synthese_data(){

        $labels = array('Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aug', 'Sep','Oct', 'Nov', 'Dec');
        $deliverSerie = [];
        $returnDeliverySerie = [];

        $bakehouse_id = (Auth::user()->bakehouse) ? Auth::user()->bakehouse->id : NULL ;

        $depenseProduct = Procurement::where('bakehouse_id',$bakehouse_id)
                                        ->where('status',1)
                                        ->sum('total_amount');

        $otherDepense = Expense::where('bakehouse_id',$bakehouse_id)
                                        ->where('is_deleted',0)
                                        ->sum('total_amount');

        $saleDeliveryTotal = Delivery::where('bakehouse_id',$bakehouse_id)
                                        ->where('status',1)
                                        ->where('is_deleted',0)
                                        ->sum('total_amount');
        $orderTotal = Order::where('bakehouse_id',$bakehouse_id)
                                ->where('status',1)
                                ->where('is_deleted',0)
                                ->sum('total_amount');

        $salesCaisseTotal = Sale::where('bakehouse_id',$bakehouse_id)
                                    ->where('is_deleted',0)
                                    ->sum('total_amount');

        $orderReturnTotal = OrderReturn::where('bakehouse_id',$bakehouse_id)
                                ->sum('total_amount');

        $trasactionTotal = Transaction::where('bakehouse_id',$bakehouse_id)
                                            ->where('status_paiement', 1)
                                            ->sum('total_amount');

        $venteNette = ($saleDeliveryTotal - $orderReturnTotal) + $orderTotal + $salesCaisseTotal;

        $customerTotal = Customer::where('bakehouse_id',$bakehouse_id)
                                    ->where('is_deleted',0)
                                    ->count();

        $supplierTotal = Supplier::where('bakehouse_id',$bakehouse_id)
                                    ->where('is_deleted',0)
                                    ->count();

        $deliveryTotal = User::where('bakehouse_id',$bakehouse_id)
                                    ->whereHas('roles', function ($query) {
                                        $query->where('name', 'livreur');
                                    })
                                    ->count();


        $technicalTotal = TechnicalSheet::where('bakehouse_id',$bakehouse_id)
                                    ->where('is_deleted',0)
                                    ->count();

        $queryStockListOne = StockProduct::selectRaw('products.price,products.unit_id, products_stock.product_id, products_stock.id, products.name AS product_name, products.image, products_stock.quantity, units.name AS unit_name')
                    ->where('products_stock.bakehouse_id', $bakehouse_id)
                    ->leftJoin('products', 'products.id', '=', 'products_stock.product_id')
                    ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                    ->where('products.type',0)
                    ->get();

        $queryStockListTwo = StockProduct::selectRaw('products.price,products.unit_id, products_stock.product_id, products_stock.id, products.name AS product_name, products.image, products_stock.quantity')
                    ->where('products_stock.bakehouse_id', $bakehouse_id)
                    ->leftJoin('products', 'products.id', '=', 'products_stock.product_id')
                    // ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                    ->where('products.type',1)
                    ->get();

        $queryDeliveryList = User::where('users.bakehouse_id', $bakehouse_id)
                    ->withSum([
                        'retours' => function ($query) {
                            $query->select(DB::raw('SUM(total_amount)'));
                        },
                        'livraisons' => function ($query) {
                            $query->where('status', 1)
                                ->select(DB::raw('SUM(total_amount)'));
                        },
                        'transactions' => function ($query) {
                            $query->where('status_paiement', 1)
                            ->select(DB::raw('SUM(total_amount)'));
                        }
                    ], 'total_amount')
                    ->whereHas('roles', function ($query) {
                        $query->where('name', 'livreur');
                    })
            ->get();

        $queryDepenseList = Product::selectRaw('products.price,products.unit_id,   products.name AS product_name, products.image,  units.name AS unit_name')
                                        ->withSum([
                                            'procurement_details' => function ($query) {
                                                $query->select(DB::raw('SUM(quantity * unit_price)'));
                                            },
                                        ], 'total_amount')
                                        ->leftJoin('units', 'units.id', '=', 'products.unit_id')
                                        ->where('products.bakehouse_id',$bakehouse_id)
                                        ->where('products.type',0)
                                        ->get();

        $deliveryBymonth = DeliveryDetails::selectRaw("SUM(delivery_details.quantity) as quantity, MONTH(delivery_details.created_at) as month")
                                                        ->leftJoin('deliveries','deliveries.id','=', 'delivery_details.delivery_id')
                                                        ->where('deliveries.bakehouse_id',$bakehouse_id)
                                                        ->whereYear('delivery_details.created_at', date('Y'))
                                                        ->groupByRaw("MONTH(delivery_details.created_at)")
                                                        ->get();

        $orderReturnByMonth = OrderReturnDetail::selectRaw("SUM(order_return_details.quantity) as quantity, MONTH(order_return_details.created_at) as month")
                                                    ->leftJoin('order_returns','order_returns.id','=', 'order_return_details.order_return_id')
                                                    ->where('order_returns.bakehouse_id',$bakehouse_id)
                                                    ->whereYear('order_return_details.created_at', date('Y'))
                                                    ->groupByRaw("MONTH(order_return_details.created_at)")
                                                    ->get();

    // STAT DU JOUR

    $saleDeliveryTotalToday = Delivery::where('bakehouse_id',$bakehouse_id)
                                    ->where('status',1)
                                    ->where('is_deleted',0)
                                    ->whereDate('created_at', Carbon::now())
                                    ->sum('total_amount');

    $orderTotalToday = Order::where('bakehouse_id',$bakehouse_id)
                            ->where('status',1)
                            ->where('is_deleted',0)
                            ->whereDate('created_at', Carbon::now())
                            ->sum('total_amount');

    $salesCaisseTotalToday = Sale::where('bakehouse_id',$bakehouse_id)
                                ->where('is_deleted',0)
                                ->whereDate('created_at', Carbon::now())
                                ->sum('total_amount');

    // $orderReturnTotalToday = OrderReturn::where('bakehouse_id',$bakehouse_id)
    //                                 ->whereDate('created_at', Carbon::now())
    //                                     ->sum('total_amount');

    $trasactionTotalToday = Transaction::where('bakehouse_id',$bakehouse_id)
                                        ->where('status_paiement', 1)
                                        ->whereDate('created_at', Carbon::now())
                                        ->sum('total_amount');

    // $depenseProductToday = Procurement::where('bakehouse_id',$bakehouse_id)
    //                                     ->where('status',1)
    //                                     ->whereDate('created_at', Carbon::now())
    //                                     ->sum('total_amount');

    // $otherDepenseToday = Expense::where('bakehouse_id',$bakehouse_id)
    //                                     ->where('is_deleted',0)
    //                                     ->whereDate('created_at', Carbon::now())
    //                                     ->sum('total_amount');

    $deliveryByDay = DeliveryDetails::selectRaw("SUM(delivery_details.quantity) as quantity,products.name,products.image")
                                            ->leftJoin('deliveries','deliveries.id','=', 'delivery_details.delivery_id')
                                            ->leftJoin('products','products.id','=', 'delivery_details.product_id')
                                                ->where('deliveries.bakehouse_id',$bakehouse_id)
                                                ->whereDate('delivery_details.created_at', Carbon::now())
                                                    ->groupByRaw("delivery_details.product_id,products.name,products.image")
                                                        ->get();


        $deliverSerie = array_fill_keys($labels, 0);
        $returnDeliverySerie = array_fill_keys($labels, 0);

        foreach ($orderReturnByMonth as $order) {
            $month = $order->month;
            $quantity = $order->quantity;
            $returnDeliverySerie[$labels[$month - 1]] = $quantity;
        }

        foreach ($deliveryBymonth as $order) {
            $month = $order->month;
            $quantity = $order->quantity;
            $deliverSerie[$labels[$month - 1]] = $quantity;
        }

        $data["depenseTotal"] = $depenseProduct + $otherDepense;
        $data["venteNetteTotale"] = $venteNette;
        $data["totalEncaisse"] = $trasactionTotal;
        $data["reliquat"] = $venteNette - $trasactionTotal;

        $data["customersTotal"] = $customerTotal;
        $data["supplierTotal"] = $supplierTotal;
        $data["deliveryTotal"] = $deliveryTotal;
        $data["technicalTotal"] = $technicalTotal;

        $data["queryStockListOne"] = $queryStockListOne;
        $data["queryStockListTwo"] = $queryStockListTwo;
        $data["queryDeliveryList"] = $queryDeliveryList;
        $data["queryDepenseList"] = $queryDepenseList;
        $data["deliverSerie"] = $deliverSerie;
        $data["returnDeliverySerie"] = $returnDeliverySerie;
        $data["labels"] = $labels;


        $data["deliveryByDay"] = $deliveryByDay;
        $data["statsToday"] = array(
                                    ["designation"=>"Vente au contoir","chiffre"=>$salesCaisseTotalToday],
                                    ["designation"=>"Commandes","chiffre"=>$orderTotalToday],
                                    ["designation"=>"Livraisons","chiffre"=>$saleDeliveryTotalToday],
                                    ["designation"=>"Encaissements","chiffre"=>$trasactionTotalToday],
                                    );

        return $data;


    }

}
