<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Ziggy;

class InertiaEmbed
{
    public static function make(): string
    {
        return <<<'typescript'
        import {Config, InputParams, Router, RouteParamsWithQueryOverload} from "ziggy-js";

        declare module "@vue/runtime-core" {
          interface ComponentCustomProperties {
            $route: (name: keyof ZiggyLaravelRoutes, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean, customZiggy?: Config) => string;
            $isRoute: (name: keyof ZiggyLaravelRoutes, params?: RouteParamsWithQueryOverload) => boolean;
            $currentRoute: () => string;
            $page: InertiaPage
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
            $page: InertiaPage
            sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];
          }
        }
        typescript;
    }
}
