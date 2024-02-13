<?php


namespace App\Core\Traits;

trait GetModelByUuid
{
    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
