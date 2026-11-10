
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained()->restrictOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('origin_id')->constrained('destinations')->restrictOnDelete();
            $table->foreignId('destination_id')->constrained('destinations')->restrictOnDelete();
            $table->dateTime('departure_at');
            $table->dateTime('arrival_at');
            $table->decimal('price', 12, 2);
            $table->enum('status', ['scheduled', 'departed', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
