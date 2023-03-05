<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prefecture_id')->constrained();
            $table->string('name');
            $table->unsignedBigInteger('all')->nullable();
            $table->unsignedBigInteger('male')->nullable();
            $table->unsignedBigInteger('female')->nullable();
            $table->unsignedBigInteger('at_2015')->nullable();
            $table->bigInteger('compared_to_2015')->nullable();
            $table->double('percentage_compared_to_2015')->nullable();
            $table->double('density')->nullable();
            $table->double('average_age')->nullable();
            $table->double('median_age')->nullable();
            $table->unsignedBigInteger('under_14')->nullable();
            $table->unsignedBigInteger('under_64')->nullable();
            $table->unsignedBigInteger('over_65')->nullable();
            $table->double('percentage_under_14')->nullable();
            $table->double('percentage_under_64')->nullable();
            $table->double('percentage_over_65')->nullable();
            $table->unsignedBigInteger('male_under_14')->nullable();
            $table->unsignedBigInteger('male_under_64')->nullable();
            $table->unsignedBigInteger('male_over_65')->nullable();
            $table->double('male_percentage_under_14')->nullable();
            $table->double('male_percentage_under_64')->nullable();
            $table->double('male_percentage_over_65')->nullable();
            $table->unsignedBigInteger('female_under_14')->nullable();
            $table->unsignedBigInteger('female_under_64')->nullable();
            $table->unsignedBigInteger('female_over_65')->nullable();
            $table->double('female_percentage_under_14')->nullable();
            $table->double('female_percentage_under_64')->nullable();
            $table->double('female_percentage_over_65')->nullable();
            $table->timestamps();

            $table->unique(['prefecture_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
