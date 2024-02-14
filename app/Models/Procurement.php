<?php

namespace App\Models;

use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Procurement extends Model
{
    use HasFactory;
    use SpatieLogsActivity;

    protected $table='procurements';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'reference',
        'supplier_id',
        'bakehouse_id',
        'tax_percentage',
        'tax_amount',
        'discount_percentage',
        'discount_amount',
        'shipping_amount',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_date',
        'status',
        'payment_status',
        'payment_method',
        'shipping_status',
        'document',
        'uuid',
        'note',
        'delivery_date',
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

    public function procurement_details()
    {
        return $this->hasMany(ProcurementDetails::class, 'procurement_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }


}
