<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent\Output;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Eloquent\Utils\EloquentProperty;

class EloquentPhp
{
    /** @var array<string,string> */
    public array $content;

    protected function __construct(
        public string $path,
    ) {
    }

    /**
     * @param  array<string,EloquentProperty[]>  $eloquents
     */
    public static function make(array $eloquents, string $path): self
    {
        $self = new self($path);

        foreach ($eloquents as $model => $eloquent) {
            $content = [];

            $content[] = '<?php';
            $content[] = '';
            $content[] = 'namespace App\Types;';
            $content[] = '';
            $content[] = '// This file is auto generated by TypescriptableLaravel.';
            $content[] = "class {$model}";
            $content[] = '{';

            $count = count($eloquent);
            $i = 0;
            foreach ($eloquent as $property) {
                $name = $property->name();
                $type = $property->type();

                $i++;
                $isLast = $i === $count;
                $isPrimitive = $self->isPrimitive($type);

                if ($isPrimitive) {
                    $content[] = $self->setField($property, $isLast);

                    continue;
                }

                if ($property->isArray()) {
                    $content[] = $self->setField($property, $isLast);

                    continue;
                }

                $type = "\\{$type}";
                $content[] = $self->setField($property, $isLast);
            }

            $content[] = '}';
            $content[] = '';

            $self->content["{$model}.php"] = implode(PHP_EOL, $content);
        }

        return $self;
    }

    public function print(): void
    {
        if (! File::exists($this->path)) {
            File::makeDirectory($this->path);
        }

        foreach ($this->content as $name => $content) {
            $path = "{$this->path}/{$name}";
            File::delete($path);
            File::put($path, $content);
        }
    }

    private function setField(EloquentProperty $property, bool $isLast): string
    {
        $comment = null;
        $field = '';
        $type = $property->type();

        if ($property->isArray()) {
            if (str_contains($type, '[]')) {
                $type = str_replace('[]', '', $type);
            }
            $comment = '    /** @var '.$type.'[] */';
            $type = 'array';
        }

        if ($comment) {
            $field = $comment.PHP_EOL;
        }

        if ($this->isDateTime($type)) {
            $type = 'DateTime';
        }

        if ($this->isClass($type)) {
            $type = "\\{$type}";
        }

        if ($property->isNullable() && $property->type() !== 'mixed') {
            $type = "?{$type}";
        }

        $field .= "    public {$type} \${$property->name()};";
        if (! $isLast) {
            $field .= PHP_EOL;
        }

        return $field;
    }

    private function isClass(string $type): bool
    {
        return class_exists($type);
    }

    private function isDateTime(string $type): bool
    {
        return $type === 'DateTime' || $type === 'datetime';
    }

    private function isPrimitive(string $type): bool
    {
        return in_array($type, [
            'int',
            'string',
            'bool',
            'float',
            'array',
            'mixed',
            'object',
            'null',
            'callable',
            'iterable',
            'resource',
            'void',
            'never',
            'false',
            'true',
            'self',
            'static',
            'parent',
        ]);
    }
}
