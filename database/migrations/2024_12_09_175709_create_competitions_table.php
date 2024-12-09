<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();

            $table->string('name')->index();
            $table->dateTime('entries_open_at');
            $table->dateTime('entries_close_at');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};
