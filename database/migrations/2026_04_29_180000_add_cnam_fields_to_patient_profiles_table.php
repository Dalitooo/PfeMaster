<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patient_profiles', function (Blueprint $table) {
            $table->string('cnam_id', 50)->nullable()->after('insurance_number');
            $table->enum('cnam_type', ['cnss', 'cnrps'])->nullable()->after('cnam_id');
        });
    }

    public function down(): void
    {
        Schema::table('patient_profiles', function (Blueprint $table) {
            $table->dropColumn(['cnam_id', 'cnam_type']);
        });
    }
};
