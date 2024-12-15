<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competition_id')->constrained('competitions');

            $table->string('name');
            $table->string('type', 10);
            $table->unsignedTinyInteger('capacity');
            $table->date('starts_on');
            $table->date('ends_on');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stages');
    }
};
