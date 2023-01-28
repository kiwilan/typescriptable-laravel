<?php

namespace Kiwilan\Typeable\Services\TypeableService\Utils;

class TypeableDbColumn
{
    public function __construct(
        public ?string $Field = null,
        public ?string $Type = null,
        public string $Null = 'YES',
        public ?string $Key = null,
        public ?string $Default = null,
        public ?string $Extra = null,
    ) {
    }

    public static function make(array|object $data): self
    {
        $field = is_array($data) ? $data['Field'] : $data->Field;
        $type = is_array($data) ? $data['Type'] : $data->Type;
        $null = is_array($data) ? $data['Null'] : $data->Null;
        $key = is_array($data) ? $data['Key'] : $data->Key;
        $default = is_array($data) ? $data['Default'] : $data->Default;
        $extra = is_array($data) ? $data['Extra'] : $data->Extra;

        return new self(
            $field ?? null,
            $type ?? null,
            $null ?? 'YES',
            $key ?? null,
            $default ?? null,
            $extra ?? null,
        );
    }
}
