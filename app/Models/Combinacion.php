<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Combinacion extends Model
{
    protected $table = 'combinaciones';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCombinacion::class, 'combinacion_id');
    }

    /**
     * Descripción legible, ej. "2 × Villa de 4 + 1 × Doble".
     */
    public function getDescripcionAttribute(): string
    {
        return $this->detalles
            ->map(fn (DetalleCombinacion $d) => $d->cantidad . ' × ' . trim($d->tipoUnid->nombre))
            ->implode(' + ');
    }
}
