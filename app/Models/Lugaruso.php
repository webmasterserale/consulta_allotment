<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LugarUso extends Model
{
    protected $connection = 'mysql_allotment';

    protected $table = 'lugaruso';

    protected $primaryKey = 'CVELUG';

    public $timestamps = false;

    protected $fillable = [
        'CORR',
        'CVELUG',
        'DESUSO',
        'BAJA',
        'ENVIAR',
        'DIRECCION',
        'CHECK_IN',
        'CHECK_OUT',
        'GRUPO',
        'IMAGE',
        'img_banner',
        'descripcion',
        'lugar',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'CHECK_IN' => 'time',
            'CHECK_OUT' => 'time',
            'CORR' => 'integer',
            'CVELUG' => 'integer',
            'GRUPO' => 'integer',
        ];
    }

    public function allotments(): HasMany
    {
        return $this->hasMany(Allotment::class, 'HOTEL', 'CVELUG');
    }
}
