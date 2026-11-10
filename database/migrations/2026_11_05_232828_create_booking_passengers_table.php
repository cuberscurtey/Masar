
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_passengers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('full_name', 200);

            $table->date('date_of_birth')->nullable();

            $table->enum('gender', [
                'male',
                'female'
            ])->nullable();

            $table->string('nationality', 100)->nullable();

            $table->enum('document_type', [
                'national_id',
                'passport',
                'family_card',
                'other'
            ])->nullable();

            $table->string('document_number', 100)->nullable();

            $table->date('document_expiry_date')->nullable();

            $table->string('phone', 30)->nullable();

            $table->enum('passenger_type', [
                'adult',
                'child'
            ])->default('adult');

            $table->decimal('fare_amount', 12, 2)->default(0);

            $table->string('ticket_number', 100)->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_passengers');
    }
};
