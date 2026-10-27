<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('name');
            $t->string('brand')->nullable();
            $t->string('model')->nullable();
            $t->json('specs')->nullable();
            $t->string('slug')->unique();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('products'); }
};
