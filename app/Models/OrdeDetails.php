<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;

class OrdeDetails extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='order_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'product_id',
        'quantity',
        'unit_price',
        'price',
        'sub_total',
        'order_id',
        'code',
        'quantity_received',
        'product_discount_amount',
        'product_tax_amount',
        'product_discount_type',
        'unit_id',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

    public function product_unit()
    {
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
