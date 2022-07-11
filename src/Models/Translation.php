<?php

namespace WeStacks\Eloquent\I18n\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'model_id',
        'model_type',
        'locale',
        'translation',
    ];

    protected $casts = [
        'translation' => 'json',
    ];

    public function model()
    {
        return $this->morphTo();
    }
}