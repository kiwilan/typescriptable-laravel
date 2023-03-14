const Routes: Record<App.Route.Name, App.Route.Entity> = {
  'login': { name: 'login', path: '/login', method: 'GET' },
  'logout': { name: 'logout', path: '/logout', method: 'POST' },
  'front.stories.show': { name: 'front.stories.show', path: '/stories/{story}', method: 'GET' },
}

// @ts-expect-error Routes is window defined
window.Routes = Routes

export {}
