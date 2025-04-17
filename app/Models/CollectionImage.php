<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionImage extends Model
{
    protected $fillable = ['collection_id', 'path', 'is_main'];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function getPathAttribute()
    {
        return asset('storage/' . $this->attributes['path']);
    }
}
