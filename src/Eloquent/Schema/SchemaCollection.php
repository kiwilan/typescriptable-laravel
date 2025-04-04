<?php

namespace Kiwilan\Typescriptable\Eloquent\Schema;

/**
 * `SchemaCollection` is a collection of schema classes.
 * Used to list all classes in a given path.
 *
 * Used by `EngineParser` and `EngineArtisan`.
 */
class SchemaCollection
{
    /**
     * @param  string[]  $skip
     * @param  SchemaClass[]  $classes
     */
    protected function __construct(
        protected string $basePath,
        protected array $skip = [],
        protected array $classes = [],
    ) {}

    /**
     * Create a new instance of `SchemaCollection`, parse the files in the given path, and return a collection of schema classes.
     *
     * @param  string[]  $skip
     */
    public static function make(string $basePath, array $skip = []): self
    {
        $self = new self($basePath, $skip);

        /** @var SchemaClass[] */
        $classes = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($basePath, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $model = SchemaClass::make($file, $basePath);
                if (! $model) {
                    continue;
                }

                $classes[$model->getFullname()] = $model;
            }
        }

        $self->classes = $self->skipNamespace($classes, $self->skip);

        return $self;
    }

    /**
     * Get the base path.
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Get all classes.
     *
     * @return SchemaClass[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Get only classes that are Laravel models.
     *
     * @return SchemaClass[]
     */
    public function getOnlyModels(): array
    {
        return array_filter($this->classes, fn (SchemaClass $item) => $item->isModel());
    }

    /**
     * Filter the classes by namespace.
     *
     * @param  SchemaClass[]  $classes
     * @param  string[]  $skip
     */
    private function skipNamespace(array $classes, array $skip): array
    {
        return array_filter($classes, fn (SchemaClass $item) => ! in_array($item->getNamespace(), $skip));
    }
}
