<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('handicaps', function (Blueprint $table) {
            $table->id();

            $table->string('bow_style');

            $table->unsignedTinyInteger('number');
            $table->unsignedSmallInteger('match_score')->index();
            $table->unsignedSmallInteger('match_allowance');
            $table->unsignedSmallInteger('set_score')->index();
            $table->unsignedSmallInteger('set_allowance');

            $table->index(['bow_style', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handicaps');
    }
};
