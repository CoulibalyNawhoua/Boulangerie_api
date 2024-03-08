<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AjustementDetails extends Model
{
    protected $table='ajustements_details';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'before_quantity',
        'after_quantity',
        'product_id',
        'ajustement_id',
        'quantity',
        'gap',
        'unit_id',
        'created_at',
        'updated_at',

    ];
}
