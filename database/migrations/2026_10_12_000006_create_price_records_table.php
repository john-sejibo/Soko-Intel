<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Append-only: bei ikibadilika, tunaongeza record mpya (hatufuti za zamani).
return new class extends Migration {
    public function up(): void {
        Schema::create('price_records', function (Blueprint $t) {
            $t->id();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('seller_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('market_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('price');           // TSh
            $t->string('currency', 3)->default('TZS');
            $t->unsignedInteger('stock_qty')->nullable();
            $t->string('source')->default('seller');   // seller | user_report | import
            $t->timestamp('recorded_at')->useCurrent();
            $t->timestamps();
            $t->index(['product_id', 'market_id', 'recorded_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('price_records'); }
};
