<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Ziggy;

class InertiaPage
{
    public static function make(): string
    {
        return <<<'typescript'
        declare interface IPage {
          props: {
            user: App.Models.User
            jetstream?: { canCreateTeams?: boolean, hasTeamFeatures?: boolean, managesProfilePhotos?: boolean, hasApiFeatures?: boolean, canUpdateProfileInformation?: boolean, canUpdatePassword?: boolean, canManageTwoFactorAuthentication?: boolean, hasAccountDeletionFeatures?: boolean }
            [x: string]: unknown;
            errors: import("@inertiajs/core").Errors & import("@inertiajs/core").ErrorBag;
          }
          url?: string;
          version?: string;
          scrollRegions?: { top: number; left: number; }[];
          rememberedState?: Record<string, unknown>;
          resolvedErrors?: import("@inertiajs/core").Errors;
        };
        typescript;
    }
}
