<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='expenses';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'libelle',
        'total_amount',
        'expense_category_id',
        'comment',
        'bakehouse_id',
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

    public function auteur()
    {
        return $this->belongsTo(User::class,'added_by','id');
    }
}
