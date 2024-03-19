<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='sales';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'customer_id',
        'bakehouse_id',
        'total_amount',
        'paid_amount',
        'due_amount',
        'balance',
        'payment_date',
        'status',
        'payment_status',
        'payment_method',
        'document',
        'uuid',
        'note',
        'add_date',
        'added_by',
        'add_ip',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'delete_ip',
        'delete_date',
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function auteur()
    {
        return $this->belongsTo(User::class, 'added_by', 'id');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }
}
