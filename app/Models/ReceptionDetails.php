<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReceptionDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='reception_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'created_at',
        'updated_at',
        'quantity',
        'unit_price',
        'price',
        'code',
        'sub_total',
        'product_discount_amount',
        'product_tax_amount',
        'product_discount_type',
        'reception_id',
        'product_id',
        'unit_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function product_unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
