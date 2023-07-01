declare global {
  interface Window {
    Routes: Record<App.Route.Name, App.Route.Link>
  }
}

// @ts-ignore
window.Routes = window.Routes || {}

export {}
