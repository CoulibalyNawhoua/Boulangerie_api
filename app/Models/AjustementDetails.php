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
        'produit_id',
        'ajustement_id',
        'quantity',
        'gap',
        'unit_id',
        'edit_ip',
        'is_deleted',
        'delete_ip',
        'delete_date',
        'created_at',
        'updated_at',

    ];
}
