<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Utils;

class TypescriptablePaginate
{
    public static function make()
    {
        return <<<'typescript'
  export type Paginate<T = any> = {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
      url: string;
      label: string;
      active: boolean;
    }[];
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
