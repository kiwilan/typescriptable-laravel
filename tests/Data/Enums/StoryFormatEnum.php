<?php

namespace Kiwilan\Typescriptable\Tests\Data\Enums;

/**
 * List of available formats.
 */
enum StoryFormatEnum
{
    public const ALLOWED_EXTENSIONS = ['mp3', 'm4b', 'pdf', 'cb7', 'cba', 'cbr', 'cbt', 'cbz', 'epub'];

    case unknown;

    case audio;

    case pdf;

    case cba;

    case epub;
}
