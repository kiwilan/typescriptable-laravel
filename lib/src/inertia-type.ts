import { writeFile } from 'node:fs'
import type { ViteTypescriptableOptions } from './vite-plugin'

export class InertiaType {
  static make(opts: ViteTypescriptableOptions) {
    const self = new InertiaType()
    const basePath = opts.inertia?.basePath || 'resources/js'
    const inertiaTypeFile = opts.inertia?.pageType || 'types-inertia.d.ts'
    const inertiaGlobalTypeFile = opts.inertia?.globalType || 'types-inertia-global.d.ts'

    if (opts.inertia?.pageType) {
      self.setFile(`${basePath}/${inertiaTypeFile}`, self.setPageType())
      // eslint-disable-next-line no-console
      console.log('Inertia types ready!')
    }

    if (opts.inertia?.globalType) {
      self.setFile(`${basePath}/${inertiaGlobalTypeFile}`, self.setGlobalType())
      // eslint-disable-next-line no-console
      console.log('Inertia global types ready!')
    }
  }

  private rootPath(): string {
    return process.cwd()
  }

  private setFile(filename: string, content: string) {
    const path = `${this.rootPath()}/${filename}`

    writeFile(path, content, (err) => {
      if (err)
        console.error(err)
    })
  }

  private setPageType(): string {
    return `// This file is auto generated by TypescriptableLaravel.
declare namespace Inertia {
  type Errors = Record<string, string>;
  type ErrorBag = Record<string, Errors>;
  declare interface Page {
    component: string;
    props: Inertia.PageProps;
    url: string;
    version: string | null;
    scrollRegions: Array<{
        top: number;
        left: number;
    }>;
    rememberedState: Record<string, unknown>;
    resolvedErrors: Inertia.Errors;
  }
  declare interface PageProps {
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
      hasEmailVerification?: boolean;
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
}
`
  }

  private setGlobalType(): string {
    return `declare module 'vue' {
  interface ComponentCustomProperties {
    $route: (route: App.Route.TypeGet) => string;
    $isRoute: (name: App.Route.TypeGet) => boolean;
    $currentRoute: () => string;
    $page: Inertia.Page
    sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];
  }
  export interface GlobalComponents {
    Head: typeof import('@inertiajs/vue3').Head,
    Link: typeof import('@inertiajs/vue3').Link,
    Route: typeof import('@kiwilan/typescriptable-laravel/vue').TypedLink,
  }
}

export {};
    `
  }
}