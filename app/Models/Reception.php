<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reception extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='receptions';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'created_at',
        'updated_at',
        'add_date',
        'added_by',
        'add_ip',
        'edited_by',
        'edit_date',
        'edit_ip',
        'is_deleted',
        'deleted_by',
        'delete_ip',
        'delete_date',
        'total_amount',
        'bakehouse_id',
        'procurement_id',
        'order_id',
        'uuid'
    ];

    public function receptionDetails()
    {
        return $this->hasMany(ReceptionDetails::class, 'reception_id', 'id');
    }

    public function procurement()
    {
        return $this->belongsTo(Procurement::class, 'procurement_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }
}
