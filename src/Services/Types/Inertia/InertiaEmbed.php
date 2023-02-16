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
    {$route}: (route: Route.Type) => string;
    {$isRoute}: (name: Route.Type) => boolean;
    {$currentRoute}: () => string;
    {$page}
    {$sessions}
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
}
typescript;
    }
}
