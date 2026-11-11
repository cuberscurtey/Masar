
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // حذف العمود القديم
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            // جعل role_id إلزامياً
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إعادة role_id لقبول NULL
            $table->unsignedBigInteger('role_id')->nullable()->change();

            // إعادة العمود القديم عند التراجع
            $table->enum('role', [
                'admin',
                'customer',
                'driver',
                'trip_supervisor',
                'accountant'
            ])->default('customer');
        });
    }
};
