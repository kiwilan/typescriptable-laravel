<?php

namespace Kiwilan\Typescriptable\Typed\Utils\Schema;

/**
 * Represents a group of PHP class.
 */
class SchemaCollection
{
    /**
     * @param  string[]  $skip
     * @param  SchemaClass[]  $items
     */
    protected function __construct(
        protected string $basePath,
        protected array $skip = [],
        protected array $items = [],
    ) {}

    /**
     * Create new instance of `SchemaCollection` from base path, like `app/Models`.
     *
     * @param  string[]  $skip  Skip namespace.
     */
    public static function make(string $basePath, array $skip = []): self
    {
        $self = new self($basePath, $skip);

        /** @var SchemaClass[] */
        $items = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $class = SchemaClass::make($file, $basePath);
                if (! $class) {
                    continue;
                }

                $items[$class->getFullname()] = $class;
            }
        }

        $self->items = $self->skipNamespace($items, $self->skip);

        return $self;
    }

    /**
     * Get base path.
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get all `SchemaClass` as array.
     *
     * @param  bool  $only_models  Get only models.
     * @return SchemaClass[]
     */
    public function getItems(bool $only_models = false): array
    {
        if ($only_models) {
            return array_filter($this->items, fn (SchemaClass $item) => $item->isModel());
        }

        return $this->items;
    }

    /**
     * @param  SchemaClass[]  $classes
     * @param  string[]  $skip
     */
    private function skipNamespace(array $classes, array $skip): array
    {
        return array_filter($classes, fn (SchemaClass $item) => ! in_array($item->getNamespace(), $skip));
    }
}
