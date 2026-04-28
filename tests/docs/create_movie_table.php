<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title')->nullable()->unique();
            $table->json('subtitles');
            $table->string('homepage')->nullable();
            $table->enum('budget', [
                BudgetEnum::high->value,
                BudgetEnum::middle->value,
                BudgetEnum::low->value,
            ])->default(BudgetEnum::high);
            $table->bigInteger('revenue')->nullable();
            $table->boolean('is_multilingual')->default(false);
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->dateTime('added_at')->nullable();
            $table->dateTime('fetched_at')->nullable();
            $table->timestamps();
        });
    }
};
