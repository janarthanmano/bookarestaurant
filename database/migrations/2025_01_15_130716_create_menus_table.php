<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->index();
            $table->dateTime('created_at');
            $table->longText('description')->nullable();
            $table->boolean('display_text')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('thumbnail', 255)->nullable();
            $table->boolean('is_vegan')->nullable();
            $table->boolean('is_vegetarian')->nullable();
            $table->string('name', 255)->index();
            $table->integer('status')->nullable();
            $table->decimal('price_per_person')->nullable();
            $table->decimal('min_spend')->nullable();
            $table->boolean('is_seated')->nullable();
            $table->boolean('is_standing')->nullable();
            $table->boolean('is_canape')->nullable();
            $table->boolean('is_mixed_dietary')->nullable();
            $table->boolean('is_meal_prep')->nullable();
            $table->boolean('is_halal')->nullable();
            $table->boolean('is_kosher')->nullable();
            $table->text('price_includes')->nullable();
            $table->text('highlight')->nullable();
            $table->boolean('available')->nullable();
            $table->integer('number_of_orders')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
