declare global {
  interface Window {
    Routes: Record<App.Route.Name, App.Route.Link>
  }
}

// @ts-expect-error - Routes is defined in the global scope
window.Routes = window.Routes || {}

export {}
