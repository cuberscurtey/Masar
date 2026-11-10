
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_seats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('bus_seat_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('booking_passenger_id')
                ->nullable()
                ->unique()
                ->constrained('booking_passengers')
                ->restrictOnDelete();

            $table->enum('status', [
                'available',
                'held',
                'booked',
                'blocked'
            ])->default('available');

            $table->dateTime('hold_expires_at')->nullable();

            $table->timestamps();

            $table->unique(['trip_id', 'bus_seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_seats');
    }
};
