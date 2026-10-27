<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('price_reports', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->cascadeOnDelete();
            $t->foreignId('market_id')->constrained()->cascadeOnDelete();
            $t->unsignedBigInteger('price');
            $t->string('status')->default('pending'); // pending | approved | rejected
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('price_reports'); }
};
