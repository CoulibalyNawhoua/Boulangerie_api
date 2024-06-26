<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='products';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'name',
        'category_id',
        'unit_id',
        'sous_famille_id',
        'price',
        'code',
        'stock_alert',
        'cost',
        'bakehouse_id',
        'type',
        'image',
        'pcb',
        'created_at',
        'updated_at',
        'add_date',
        'added_by',
        'add_ip',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'delete_ip',
        'delete_date',
        'uuid'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }


    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }

    public function procurement_details()
    {
        return $this->hasMany(ProcurementDetails::class, 'product_id', 'id');
    }

}
