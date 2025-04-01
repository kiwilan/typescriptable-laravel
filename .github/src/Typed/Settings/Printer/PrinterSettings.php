<?php

namespace Kiwilan\Typescriptable\Typed\Settings\Printer;

use Kiwilan\Typescriptable\Typed\Settings\Schemas\SettingsItem;
use Kiwilan\Typescriptable\Typescriptable;

class PrinterSettings
{
    /**
     * @param  array<string,SettingsItem>  $items
     */
    public static function make(array $items): string
    {
        $self = new self;

        /** @var string[] */
        $content = [];

        $content = array_merge($content, Typescriptable::TS_HEAD);
        $content[] = 'declare namespace App.Settings {';

        foreach ($items as $model => $item) {
            $content[] = "  export interface {$model} {";
            foreach ($item->properties() as $field => $property) {
                $field = $property->isNullable() ? "{$field}?" : $field;
                $content[] = "    {$field}: {$property->typescriptType()}";
            }
            $content[] = '  }';
        }
        $content[] = '}';
        $content[] = '';

        return implode(PHP_EOL, $content);
    }
}
