<?php

namespace App\Core\Traits;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Reception;
use App\Models\Ajustement;
use App\Models\Gaspillage;
use App\Models\Procurement;

trait GeneratedCodeTrait {

    public function referenceGenerator($table) {

        $code='';
        $code_generer=random_int(000000,999999);
            $nombre_elt='';
            // $prefix = Carbon::now()->format('Y-m');
            // $date = Carbon::now()->format('m-Y');
            switch ($table) {
                case 'Procurement':
                    $nombre_elt=Procurement::get()->count();
                    $nombre_elt++;
                    $prefix= 'CMD-FR';
                break;
                case 'Reception':
                    $nombre_elt=Reception::get()->count();
                    $nombre_elt++;
                    // $prefix= 'RCPT';
                break;
                case 'Order':
                    $nombre_elt=Order::get()->count();
                    $nombre_elt++;
                    // $prefix= 'CMD-DP';
                break;
            }
            $code=$prefix.'-'.$code_generer.$nombre_elt;
            return $code;

    }

}
