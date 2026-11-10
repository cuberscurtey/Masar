
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bus_seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->cascadeOnDelete();
            $table->string('seat_number');
            $table->integer('row_number');
            $table->integer('column_number');
            $table->enum('seat_type', ['regular', 'premium'])->default('regular');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['bus_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bus_seats');
    }
};
