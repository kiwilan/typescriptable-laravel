<?php

namespace Kiwilan\Typescriptable\Tests\Data\Enums;

enum PublishStatusEnum: string
{
    case draft = 'draft';

    case scheduled = 'scheduled';

    case published = 'published';
}
