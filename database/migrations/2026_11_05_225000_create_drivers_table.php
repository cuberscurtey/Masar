
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();

            $table->string('full_name');
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();

            $table->string('license_number')->unique();
            $table->date('license_expiry');

            $table->text('address')->nullable();
            $table->string('emergency_contact')->nullable();

            $table->enum('status', [
                'available',
                'on_trip',
                'leave',
                'suspended'
            ])->default('available');

            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
