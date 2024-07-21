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
            $table->string('format')->nullable();

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
            $table->foreignId('tag_id')->constrained('tags')->cascadeOnDelete();
            $table->foreignId('story_id')->constrained('stories')->cascadeOnDelete();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('url')->nullable();
            $table->text('content')->nullable();

            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            $table->foreignId('comment_id')
                ->nullable()
                ->constrained('comments')
                ->onDelete('no action');

            $table->morphs('commentable');

            $table->timestamps();
        });

        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tmdb_id')->nullable();
            $table->string('credit_id')->nullable();
            $table->unsignedSmallInteger('gender')->default(1);
            $table->string('name')->nullable();
            $table->string('original_name')->nullable();
            $table->float('popularity')->nullable();
            $table->string('poster')->nullable();
            $table->string('poster_tmdb')->nullable();
            $table->string('poster_color')->nullable();
            $table->string('tmdb_url')->nullable();

            $table->timestamps();
        });

        Schema::create('memberables', function (Blueprint $table) {
            $table->foreignId('member_id')->index();
            $table->string('character')->nullable();
            $table->string('job')->nullable();
            $table->string('department')->nullable();
            $table->integer('order')->nullable();
            $table->boolean('is_adult')->default(false);
            $table->string('known_for_department')->nullable();
            $table->boolean('is_crew')->nullable();
            $table->ulidMorphs('memberable');
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->integer('tmdb_id')->nullable();
            $table->string('title')->nullable()->unique();
            $table->integer('year')->nullable();
            $table->json('subtitles');
            $table->string('slug')->unique();
            $table->string('french_title')->nullable();
            $table->string('original_title')->nullable();
            $table->date('release_date')->nullable();
            $table->string('original_language')->nullable();
            $table->text('overview')->nullable();
            $table->float('popularity')->nullable();
            $table->boolean('is_adult')->nullable();
            $table->string('homepage')->nullable();
            $table->string('tagline')->nullable();
            $table->string('status')->nullable();
            $table->string('certification')->nullable();
            $table->string('tmdb_url')->nullable();

            $table->string('imdb_id')->nullable();
            $table->integer('runtime')->nullable();
            $table->enum('budget', [
                PublishStatusEnum::draft->value,
                PublishStatusEnum::scheduled->value,
                PublishStatusEnum::published->value,
            ])->default(PublishStatusEnum::draft);
            $table->bigInteger('revenue')->nullable();
            $table->string('edition')->nullable();
            $table->string('version')->nullable();
            $table->string('library')->nullable();
            $table->boolean('is_multilingual')->default(false);

            $table->string('poster')->nullable();
            $table->string('poster_tmdb')->nullable();
            $table->string('poster_color')->nullable();

            $table->string('backdrop')->nullable();
            $table->string('backdrop_tmdb')->nullable();

            $table->foreignId('author_id')->nullable()->constrained('authors')->nullOnDelete();

            $table->dateTime('added_at')->nullable();
            $table->dateTime('fetched_at')->nullable();
            $table->boolean('fetched_has_failed')->default(false);

            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->morphs('model');
            $table->uuid()->nullable()->unique();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->string('conversions_disk')->nullable();
            $table->unsignedBigInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('generated_conversions');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable()->index();

            $table->nullableTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('stories');
        Schema::dropIfExists('authors');
        Schema::dropIfExists('chapters');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('story_tag');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('members');
        Schema::dropIfExists('memberables');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('media');
    }
};
