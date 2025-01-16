<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_at',
        'description',
        'display_text',
        'image',
        'thumbnail',
        'is_vegan',
        'is_vegetarian',
        'name',
        'status',
        'price_per_person',
        'min_spend',
        'is_seated',
        'is_standing',
        'is_canape',
        'is_mixed_dietary',
        'is_meal_prep',
        'is_halal',
        'is_kosher',
        'price_includes',
        'highlight',
        'available',
        'number_of_orders',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'timestamp',
        'display_text' => 'boolean',
        'is_vegan' => 'boolean',
        'is_vegetarian' => 'boolean',
        'price_per_person' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'is_seated' => 'boolean',
        'is_standing' => 'boolean',
        'is_canape' => 'boolean',
        'is_mixed_dietary' => 'boolean',
        'is_meal_prep' => 'boolean',
        'is_halal' => 'boolean',
        'is_kosher' => 'boolean',
        'available' => 'boolean',
    ];

    public function cuisines(): BelongsToMany
    {
        return $this->belongsToMany(Cuisine::class, 'cuisine_menu');
    }

    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }
}
