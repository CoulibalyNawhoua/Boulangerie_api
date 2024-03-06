<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReturnDetail extends Model
{
    protected $table='ajustements_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'order_return_id',
        'product_id',
        'quantity',
        'price',
        'unit_id',
        'in_stock',
        'created_at',
        'updated_at',

    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
