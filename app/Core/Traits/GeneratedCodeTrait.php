<?php

namespace App\Core\Traits;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Ajustement;
use App\Models\Procurement;

trait GeneratedCodeTrait {

    public function referenceGenerator($table) {

        $code='';
        $code_generer=random_int(000000,999999);
            $nombre_elt='';
            switch ($table) {
                case 'Procurement':
                    $nombre_elt=Procurement::get()->count();
                    $nombre_elt++;
                    $prefix= 'CMD-FR';
                break;
                case 'Sale':
                    $nombre_elt=Sale::get()->count();
                    $nombre_elt++;
                    $prefix= 'SA';
                break;
                case 'Order':
                    $nombre_elt=Order::get()->count();
                    $nombre_elt++;
                    $prefix= 'CMD';
                break;
                case 'Ajustement':
                    $nombre_elt=Ajustement::get()->count();
                    $nombre_elt++;
                    $prefix= 'AJUST';
                break;
                case 'Delivery':
                    $nombre_elt=Delivery::get()->count();
                    $nombre_elt++;
                    $prefix= 'LIV';
                break;
            }
            $code=$prefix.'-'.$code_generer.$nombre_elt;
            return $code;

    }

}
