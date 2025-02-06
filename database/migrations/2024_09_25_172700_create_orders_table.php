<?php

use App\Models\Product;
use App\Models\Ship_address;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_hash')->unique();
            $table->foreignIdFor(User::class)->constrained();
            $table->unsignedInteger('quantity');
            $table->decimal('total_amount', 10, 2);
            $table->tinyInteger('status')->default(0); // 0.chờ thanh toán, 1.thành công, 2. thất bại/đã hủy
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
