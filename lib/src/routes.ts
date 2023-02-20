const Routes: Record<Route.Name, Route.Entity> = {
  login: { name: 'login', path: '/login', method: 'GET' },
  logout: { name: 'logout', path: '/logout', method: 'POST' },
  story: { name: 'story', path: '/story/{story}', method: 'GET' },
}

// @ts-expect-error Routes is window defined
window.Routes = Routes

export {}
