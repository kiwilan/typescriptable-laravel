<?php

namespace Kiwilan\Typescriptable\Services\Types\Inertia;

class InertiaEmbed
{
    public static function make(): string
    {
        return <<<'typescript'
        declare module "@vue/runtime-core" {
          interface ComponentCustomProperties {
            $route: (name: Route.Name, params?: Route.Params[Route.Name]) => string;
            $isRoute: (name: Route.Name, params?: Route.Params[Route.Name]) => boolean;
            $currentRoute: () => string;
            $page: Inertia.Page
            sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];
          }
        }
        typescript;
    }

    public static function native()
    {
        return <<<'typescript'
        declare module "@vue/runtime-core" {
          interface ComponentCustomProperties {
            $page: Inertia.Page
            sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];
          }
        }
        typescript;
    }
}
