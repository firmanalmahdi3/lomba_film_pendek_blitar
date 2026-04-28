<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('candidates', function (Blueprint $table) {
        $table->dropColumn(['origin', 'photo']);
    });
}

public function down(): void
{
    Schema::table('candidates', function (Blueprint $table) {
        $table->string('origin')->nullable();
        $table->string('photo')->nullable();
    });
}
};
