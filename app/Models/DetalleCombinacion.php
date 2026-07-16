<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetalleCombinacion extends Model
{
    protected $table = 'detalle_combinaciones';

    public $timestamps = false;

    protected $guarded = [];

    public function combinacion(): BelongsTo
    {
        return $this->belongsTo(Combinacion::class, 'combinacion_id');
    }

    public function tipoUnid(): BelongsTo
    {
        return $this->belongsTo(TipoUnid::class, 'tipo_unid_id');
    }
}
