<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('competition_id')->constrained('competitions');
            $table->foreignId('person_id')->constrained('people');

            $table->string('bow_style');
            $table->unsignedTinyInteger('initial_handicap')->nullable();
            $table->unsignedTinyInteger('current_handicap')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
