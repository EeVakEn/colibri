<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->decimal('subscription_price', 8, 2)->nullable()->change();

        });
    }


    public function down(): void
    {
        Schema::table('channels', function (Blueprint $table) {
            $table->decimal('subscription_price', 8, 2)->default(0)->change();
        });
    }
};
