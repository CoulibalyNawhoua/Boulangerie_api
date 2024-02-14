<?php

namespace App\Core\Traits;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Procurement;
use App\Models\Sale;

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
                    // $prefix= 'CMD-DP';
                break;
            }
            $code=$prefix.'-'.$code_generer.$nombre_elt;
            return $code;

    }

}
