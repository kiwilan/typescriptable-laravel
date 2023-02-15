<?php

namespace Kiwilan\Typescriptable\Services\Types\Inertia;

class InertiaPage
{
    public static function make(): string
    {
        return <<<'typescript'
type Errors = Record<string, string>;
type ErrorBag = Record<string, Errors>;
declare interface InertiaPage {
  component: string;
  props: Inertia.Props;
  url: string;
  version: string | null;
  scrollRegions: Array<{
      top: number;
      left: number;
  }>;
  rememberedState: Record<string, unknown>;
  resolvedErrors: Inertia.Errors;
}
declare interface InertiaPageProps {
  user: App.Models.User;
  jetstream?: {
      canCreateTeams?: boolean;
      hasTeamFeatures?: boolean;
      managesProfilePhotos?: boolean;
      hasApiFeatures?: boolean;
      canUpdateProfileInformation?: boolean;
      canUpdatePassword?: boolean;
      canManageTwoFactorAuthentication?: boolean;
      hasAccountDeletionFeatures?: boolean;
      flash?: {
      bannerStyle?: string;
      banner?: string;
      message?: string;
      style?: string;
      };
  };
  [key: string]: unknown
  errors: Inertia.Errors & Inertia.ErrorBag;
}
typescript;
    }
}
