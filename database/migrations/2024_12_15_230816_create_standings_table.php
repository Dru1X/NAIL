<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stage_id')->constrained('stages');
            $table->foreignId('entry_id')->constrained('entries');

            $table->unsignedTinyInteger('matches_played')->default(0);
            $table->unsignedTinyInteger('matches_won')->default(0);
            $table->unsignedTinyInteger('matches_drawn')->default(0);
            $table->unsignedTinyInteger('matches_lost')->default(0);
            $table->unsignedMediumInteger('match_points')->default(0);
            $table->unsignedMediumInteger('match_points_adjusted')->default(0);
            $table->unsignedTinyInteger('bonus_points')->default(0);
            $table->unsignedTinyInteger('league_points')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('standings');
    }
};
