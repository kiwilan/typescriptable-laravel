declare module 'vue' {
  interface ComponentCustomProperties {
    $route: (route: App.Route.Name) => string;
    $isRoute: (name: App.Route.Name) => boolean;
    $currentRoute: () => App.Route.Link | undefined;
    $to: (name: App.Route.Name, params?: App.Route.Params[App.Route.Name]) => string;
    // @ts-ignore
    $page: Inertia.Page
    sessions: { agent: { is_desktop: boolean; browser: string; platform: string; }, ip_address: string; is_current_device: boolean; last_active: string; }[];
  }
  export interface GlobalComponents {
    Head: typeof import('@inertiajs/vue3').Head,
    Link: typeof import('@inertiajs/vue3').Link,
  }
}

export {};
