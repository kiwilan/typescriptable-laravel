<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Tests\Data\Enums\PublishStatusEnum;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });

        Schema::create('stories', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->text('abstract')->nullable();
            $table->string('original_link')->nullable();
            $table->text('picture')->nullable();
            $table->string('status')->default(PublishStatusEnum::draft->value);
            $table->dateTime('published_at')->nullable();

            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();

            $table->timestamps();
        });

        Schema::create('authors', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique()->index()->nullable();
            $table->string('avatar')->nullable();

            $table->timestamps();
        });

        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->text('content')->nullable();
            $table->integer('time_to_read')->nullable();
            $table->integer('number')->nullable();

            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('slug')->unique()->index()->nullable();
            $table->string('picture')->nullable();
            $table->string('color')->nullable();

            $table->timestamps();
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
        });

        Schema::table('chapters', function (Blueprint $table) {
            $table->foreignId('story_id')->nullable()->constrained('stories')->nullOnDelete();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('story_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tag_id')->constrained();
            $table->foreignId('story_id')->constrained();
        });
    }
};
