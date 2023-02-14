<?php

namespace Kiwilan\Typescriptable\Services\Types\Utils;

class LaravelPaginateType
{
    public static function make()
    {
        return <<<'typescript'
  export type PaginateLink = {
    url: string;
    label: string;
    active: boolean;
  };
  export type Paginate<T = any> = {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: App.PaginateLink[];
    next_page_url: string;
    path: string;
    per_page: number;
    prev_page_url: string;
    to: number;
    total: number;
  };
typescript;
    }
}
