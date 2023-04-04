<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Output;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;
use Kiwilan\Typescriptable\Typed\Utils\LaravelPaginateType;
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

        $content[] = '// This file is auto generated by TypescriptableLaravel.';
        $content[] = 'declare namespace App {';
        $content[] = '  declare namespace Models {';

        foreach ($eloquents as $model => $eloquent) {
            $content[] = "    export type {$model} = {";

            foreach ($eloquent as $field => $property) {
                $field = $property->isNullable ? "{$field}?" : $field;
                $content[] = "      {$field}: {$property->typeTs}";
            }
            $content[] = '    }';
        }
        $content[] = '  }';

        if (TypescriptableConfig::modelsPaginate()) {
            $content[] = LaravelPaginateType::make();
        }
        $content[] = '}';

        $self->content = implode(PHP_EOL, $content);

        return $self;
    }

    public function print(): void
    {
        File::put($this->path, $this->content);
    }
}