<?php

return new class extends \Illuminate\Database\Migrations\Migration
{
    public function up(): void
    {
        \Illuminate\Support\Facades\Schema::create('movies', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('title')->nullable()->unique();
            $table->json('subtitles');
            $table->string('homepage')->nullable();
            $table->enum('budget', [
                \Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum::high->value,
                \Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum::middle->value,
                \Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum::low->value,
            ])->default(\Kiwilan\Typescriptable\Tests\Data\Enums\BudgetEnum::high);
            $table->bigInteger('revenue')->nullable();
            $table->boolean('is_multilingual')->default(false);
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->dateTime('added_at')->nullable();
            $table->dateTime('fetched_at')->nullable();
            $table->timestamps();
        });
    }
};
