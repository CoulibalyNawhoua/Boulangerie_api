<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductHistory extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='products_histories';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'produit_id',
        'quantity',
        'gap',
        'reception_id',
        'order_id',
        'quantity_after',
        'unit_id',
        'quantity',
        'quantity_before',
        'type',
        'total_price',
        'description',
        'unit_price',
        'product_id',
        'bakehouse_id',
        'ajustement_id',
        'add_date',
        'added_by',
        'add_ip',
        'created_at',
        'updated_at',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'deleted_by',
        'delete_ip',
        'delete_date',
        'code',
        'uuid',
    ];
}
