<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

// Tabla polimorfica de imagenes
class Image extends Model
{
    protected $fillable = [
        'path',
        'disk',
        'name',
        'imageable_type',
        'imageable_id',
    ];

    /**
     * Relacion polimorfica
     *
     * @return MorphTo
     * 
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
