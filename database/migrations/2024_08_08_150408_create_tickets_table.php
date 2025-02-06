<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); 
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('name', 50); 
            $table->string('image', 255); 
            $table->dateTime('startday'); 
            $table->dateTime('enday'); 
            $table->integer('quantity'); 
            $table->unsignedInteger('sell_quantity')->default(0);
            $table->decimal('price', 8, 2); 
            $table->string('description', 250)->nullable(); 
            $table->string('nguoitochuc')->nullable(); 
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
