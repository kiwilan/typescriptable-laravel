<?php

namespace Kiwilan\Typescriptable\Typed\Setting;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typescriptable;

class SettingTypescript
{
    protected function __construct(
        public string $path,
        public string $content = '',
    ) {
    }

    /**
     * @param  array<string,SettingItem>  $items
     */
    public static function make(array $items, string $path): self
    {
        $self = new self($path);

        /** @var string[] */
        $content = [];

        $content = array_merge($content, Typescriptable::TS_HEAD);
        $content[] = 'declare namespace App.Settings {';

        foreach ($items as $model => $item) {
            $content[] = "  export interface {$model} {";
            foreach ($item->properties as $field => $property) {
                $field = $property->isNullable ? "{$field}?" : $field;
                $content[] = "    {$field}: {$property->typeTs}";
            }
            $content[] = '  }';
        }
        $content[] = '}';
        $content[] = '';

        $self->content = implode(PHP_EOL, $content);

        return $self;
    }

    public function print(): void
    {
        if (! File::exists(dirname($this->path))) {
            File::makeDirectory(dirname($this->path));
        }
        File::delete($this->path);

        File::put($this->path, $this->content);
    }
}
