<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();

            $table->foreignId('stage_id')->constrained('stages');

            $table->string('name');
            $table->date('starts_on');
            $table->date('ends_on');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rounds');
    }
};
