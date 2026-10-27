<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sellers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('market_id')->constrained()->cascadeOnDelete();
            $t->string('business_name');
            $t->string('phone')->nullable();
            $t->boolean('is_verified')->default(false);
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sellers'); }
};
