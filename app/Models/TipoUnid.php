<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUnid extends Model
{
    protected $connection = 'mysql_allotment';
    
    protected $table = 'tipo_unid';

    public $timestamps = false;

    protected $guarded = [];
}
