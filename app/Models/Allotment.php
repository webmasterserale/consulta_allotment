<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allotment extends Model
{
    protected $table = 'allotment';

    protected $primaryKey = 'corr';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'entra' => 'date',
            'sale' => 'date',
            'CREACION' => 'date',
            'MODIFICA' => 'date',
            'fe1' => 'date',
            'fe2' => 'date',
            'fe3' => 'date',
            'fe4' => 'date',
            'fe5' => 'date',
            'fe6' => 'date',
            'fe7' => 'date',
            'update_estado' => 'datetime',
        ];
    }
}
