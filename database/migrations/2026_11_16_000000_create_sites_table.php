<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type', 20);
            $table->string('city')->nullable();
            $table->text('prompt');
            $table->string('theme', 20)->default('indigo');
            $table->string('font', 20)->default('sans');
            $table->unsignedSmallInteger('variant')->default(0);
            $table->json('content');
            $table->json('sections');
            $table->boolean('published')->default(false);
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
