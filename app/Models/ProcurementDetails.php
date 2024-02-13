<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;


    protected $table='procurement_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'procurement_id',
        'product_id',
        'bakehouse_id',
        'name',
        'code ',
        'quantity',
        'quantity_already_received',
        'price',
        'unit_price',
        'sub_total',
        'product_discount_amount',
        'product_discount_type',
        'product_tax_amount',
        'unit_id',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
