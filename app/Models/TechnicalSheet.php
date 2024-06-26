<?php

namespace App\Models;

use App\Core\Traits\UuidGenerator;
use App\Core\Traits\GetModelByUuid;
use App\Core\Traits\SpatieLogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TechnicalSheet extends Model
{
    use HasFactory;
    use SpatieLogsActivity;
    use UuidGenerator;
    use GetModelByUuid;

    protected $table='technical_sheet';
    protected $primaryKey="id";
    protected $fillable=[
        'id',
        'comment',
        'bakehouse_id',
        'date',
        'time',
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
        'uuid',
    ];


    public function technical_sheet_details()
    {
        return $this->hasMany(TechnicalSheetDetails::class, 'technical_sheet_id', 'id');
    }

}
