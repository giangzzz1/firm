<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('wallet', 100)->nullable()->unique();
            $table->string('fullname')->nullable();
            $table->date('birth_day')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone', 15)->nullable();
            $table->integer('point')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('role')->default(0); // 0: user, 1: nhân viên, 2: admin
            $table->boolean('is_active')->default(1); // 0: khóa, 1: active
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
