<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kana')->after('name');
            $table->string('postal_code');
            $table->string('address');
            $table->string('phone_number');
            $table->date('birthday')->nullable();
            $table->string('occupation')->nullable();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kana', 'postal_code', 'address', 'phone_number', 'birthday', 'occupation']);
        });
    }
};
