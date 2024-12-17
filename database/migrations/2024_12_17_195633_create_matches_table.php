<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('match_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('round_id')->constrained('rounds');
            $table->foreignId('left_score_id')->constrained('scores');
            $table->foreignId('right_score_id')->constrained('scores');
            $table->foreignId('winner_id')->nullable()->constrained('entries');

            $table->dateTime('shot_at')->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};
