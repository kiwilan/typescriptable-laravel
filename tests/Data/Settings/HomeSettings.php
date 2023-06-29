<?php

namespace Kiwilan\Typescriptable\Tests\Data\Settings;

class HomeSettings extends Settings
{
    public ?string $hero_title_main = null;

    public ?string $hero_title = null;

    public ?string $hero_subtitle = null;

    public ?string $hero_text = null;

    public ?string $hero_subtext = null;

    public ?int $feed_main_id = null;

    public ?string $social_mail = null;

    public ?string $social_rss = null;

    public ?string $social_facebook = null;

    public ?string $social_twitter = null;

    public ?string $social_spotify = null;

    public ?string $social_youtube = null;

    public static function group(): string
    {
        return 'home';
    }
}
