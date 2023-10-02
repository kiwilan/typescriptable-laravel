<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Output;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Utils\LaravelPaginateType;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

class EloquentTypescript
{
    protected function __construct(
        public string $path,
        public string $content = '',
    ) {
    }

    /**
     * @param  array<string,EloquentProperty[]>  $eloquents
     */
    public static function make(array $eloquents, string $path): self
    {
        $self = new self($path);

        /** @var string[] */
        $content = [];

        $content = array_merge($content, Typescriptable::TS_HEAD);
        $content[] = 'declare namespace App.Models {';

        foreach ($eloquents as $model => $eloquent) {
            $content[] = "  export interface {$model} {";

            foreach ($eloquent as $field => $property) {
                $field = $property->isNullable() ? "{$field}?" : $field;
                $content[] = "    {$field}: {$property->typeTs()}";
            }
            $content[] = '  }';
        }

        $content[] = '}';
        $content[] = '';

        if (TypescriptableConfig::modelsPaginate()) {
            $content[] = 'declare namespace App {';
            $content[] = LaravelPaginateType::make();
            $content[] = '}';
        }
        $content[] = '';

        $self->content = implode(PHP_EOL, $content);

        return $self;
    }

    public function print(bool $delete = false): void
    {
        if (! File::exists(dirname($this->path))) {
            File::makeDirectory(dirname($this->path));
        }
        if ($delete) {
            File::delete($this->path);
        }

        File::put($this->path, $this->content);
    }
}
