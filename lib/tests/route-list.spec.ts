import { expect, it } from 'vitest'
import { RouteList } from '../src/methods'
import { Routes } from '../routes'

it('can get all routes', async () => {
  const list = RouteList.make(Routes).getAllRoutes()

  expect(list).not.toBe(undefined)
  expect(list?.home.name).toBe('home')
})

// it('can find routes', async () => {
//   const list = RouteList.make(Routes)

//   const routeFromHome = list.getRouteFromUrl('/')
//   const routeFromPosts = list.getRouteFromUrl('/blog')
//   const routeFromPostsSlug = list.getRouteFromUrl('/blog/my-post')

//   expect(routeFromHome?.name).toBe('home')
//   expect(routeFromHome?.path).toBe('/')
//   expect(routeFromHome?.params).toBe(undefined)
//   expect(routeFromHome?.method).toBe('GET')

//   expect(routeFromPosts?.name).toBe('posts.index')
//   expect(routeFromPosts?.path).toBe('/blog')
//   expect(routeFromPosts?.params).toBe(undefined)
//   expect(routeFromPosts?.method).toBe('GET')

//   expect(routeFromPostsSlug?.name).toBe('posts.show')
//   expect(routeFromPostsSlug?.path).toBe('/blog/{post_slug}')
//   expect(routeFromPostsSlug?.params).toEqual({ post_slug: 'string' })
//   expect(routeFromPostsSlug?.method).toBe('GET')

//   expect(routeFromHome?.name).toBe('home')
//   expect(routeFromHome?.path).toBe('/')
//   expect(routeFromHome?.params).toBe(undefined)
//   expect(routeFromHome?.method).toBe('GET')
// })

// it('can get route', async () => {
//   const list = RouteList.make(Routes)

//   const home = list.getRoute('home')
//   const postsSlug = list.getRoute('posts.show')

//   expect(home?.name).toBe('home')
//   expect(home?.path).toBe('/')
//   expect(home?.params).toBe(undefined)
//   expect(home?.method).toBe('GET')

//   expect(postsSlug?.name).toBe('posts.show')
//   expect(postsSlug?.path).toBe('/blog/{post_slug}')
//   expect(postsSlug?.params).toEqual({ post_slug: 'string' })
//   expect(postsSlug?.method).toBe('GET')
// })
