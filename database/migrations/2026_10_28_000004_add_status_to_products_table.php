<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $t) {
            $t->string('status')->default('approved'); // approved | pending
            $t->unsignedBigInteger('created_by')->nullable();
        });
    }
    public function down(): void {
        Schema::table('products', fn (Blueprint $t) => $t->dropColumn(['status', 'created_by']));
    }
};
