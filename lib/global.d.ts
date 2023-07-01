declare global {
  interface Window {
    Routes: Record<App.Route.Name, App.Route.Entity>
  }
}

// @ts-ignore
window.Routes = window.Routes || {}

export {}
