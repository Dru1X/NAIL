<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('entry_id')->constrained('entries');

            $table->unsignedTinyInteger('handicap_before');
            $table->unsignedTinyInteger('handicap_after');
            $table->unsignedMediumInteger('allowance');
            $table->unsignedTinyInteger('match_points');
            $table->unsignedMediumInteger('match_points_adjusted');
            $table->unsignedTinyInteger('bonus_points');
            $table->unsignedTinyInteger('league_points');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
