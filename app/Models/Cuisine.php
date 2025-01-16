<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuisine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'int';

    public function menus(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id');
    }
}
