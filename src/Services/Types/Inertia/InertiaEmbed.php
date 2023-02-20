<?php

namespace Kiwilan\Typescriptable\Services\Types\Inertia;

class InertiaEmbed
{
    const PAGE = '$page: Inertia.Page';

    const SESSIONS = 'sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];';

    public static function make(): string
    {
        $route = '$route';
        $isRoute = '$isRoute';
        $currentRoute = '$currentRoute';
        $page = self::PAGE;
        $sessions = self::SESSIONS;

        return <<<typescript
declare module 'vue' {
  interface ComponentCustomProperties {
    {$route}: (route: Route.TypeGet) => string;
    {$isRoute}: (name: Route.TypeGet) => boolean;
    {$currentRoute}: () => string;
    {$page}
    {$sessions}
  }
  export interface GlobalComponents {
    Head: typeof import('@inertiajs/vue3').Head,
    Link: typeof import('@inertiajs/vue3').Link,
    TypedLink: typeof import('@kiwilan/vite-plugin-steward-laravel/vue').TypedLink,
  }
}
typescript;
    }

    public static function native()
    {
        $page = self::PAGE;
        $sessions = self::SESSIONS;

        return <<<typescript
declare module "@vue/runtime-core" {
  interface ComponentCustomProperties {
    {$sessions}
  }
  export interface GlobalComponents {
    Head: typeof import('@inertiajs/vue3').Head,
    Link: typeof import('@inertiajs/vue3').Link,
  }
}
typescript;
    }
}
