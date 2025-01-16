<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price_per_person' => ['required', 'numeric'],
            'min_spend' => ['required', 'numeric'],
            'created_at' => ['required'],
            'description' => ['required', 'string'],
            'display_text' => ['required'],
            'image' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'string', 'max:255'],
            'is_vegan' => ['required'],
            'is_vegetarian' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer'],
            'price_per_person' => ['required', 'numeric'],
            'min_spend' => ['required', 'numeric'],
            'is_seated' => ['required'],
            'is_standing' => ['required'],
            'is_canape' => ['required'],
            'is_mixed_dietary' => ['required'],
            'is_meal_prep' => ['required'],
            'is_halal' => ['required'],
            'is_kosher' => ['required'],
            'price_includes' => ['required', 'string'],
            'highlight' => ['required', 'string'],
            'available' => ['required'],
            'number_of_orders' => ['required', 'integer'],
            'created_at' => ['required'],
            'description' => ['required', 'string'],
            'display_text' => ['required'],
            'image' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'string', 'max:255'],
            'is_vegan' => ['required'],
            'is_vegetarian' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'integer'],
            'price_per_person' => ['required', 'numeric'],
            'min_spend' => ['required', 'numeric'],
            'is_seated' => ['required'],
            'is_standing' => ['required'],
            'is_canape' => ['required'],
            'is_mixed_dietary' => ['required'],
            'is_meal_prep' => ['required'],
            'is_halal' => ['required'],
            'is_kosher' => ['required'],
            'price_includes' => ['required', 'string'],
            'highlight' => ['required', 'string'],
            'available' => ['required'],
            'number_of_orders' => ['required', 'integer'],
        ];
    }
}
