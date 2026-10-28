<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('sellers', function (Blueprint $t) {
            $t->string('whatsapp')->nullable();
            $t->string('address')->nullable();
            $t->decimal('latitude', 10, 7)->nullable();
            $t->decimal('longitude', 10, 7)->nullable();
            $t->text('description')->nullable();
            $t->string('opening_hours')->nullable();
            $t->string('status')->default('active'); // active | suspended
        });
    }
    public function down(): void {
        Schema::table('sellers', fn (Blueprint $t) => $t->dropColumn(['whatsapp', 'address', 'latitude', 'longitude', 'description', 'opening_hours', 'status']));
    }
};
