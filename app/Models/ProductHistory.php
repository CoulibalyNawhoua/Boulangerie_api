<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductHistory extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='products_histories';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'unit_id',
        'quantity',
        'type',
        'price',
        'product_id',
        'bakehouse_id',
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
        'uuid',
        'technical_sheet_id',
    ];

    public function technical_sheet()
    {
        return $this->belongsTo(TechnicalSheet::class,'technical_sheet_id', 'id');
    }
}
