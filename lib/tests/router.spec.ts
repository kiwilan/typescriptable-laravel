import { expect, it } from 'vitest'
import { LaravelRouter } from '../src/methods'
import { Routes } from '../routes'

it('can get all routes', async () => {
  const list = LaravelRouter.create(Routes).getAllRoutes()

  expect(list).not.toBe(undefined)
  expect(list?.home.name).toBe('home')
})

it('can use bind route', async () => {
  const list = LaravelRouter.create(Routes)

  list.getRouteBind({
    name: 'feeds.show',
    params: {
      feed_slug: 'string',
    },
  })
})

it('can find routes', async () => {
  const list = LaravelRouter.create(Routes)

  const routeFromHome = list.getRouteFromUrl('/')
  const routeFromPosts = list.getRouteFromUrl('/blog')
  const routeFromPostsSlug = list.getRouteFromUrl('/blog/my-post')

  expect(routeFromHome?.name).toBe('home')
  expect(routeFromHome?.path).toBe('/')
  expect(routeFromHome?.params).toBe(undefined)
  expect(routeFromHome?.methods).toStrictEqual(['GET'])

  expect(routeFromPosts?.name).toBe('posts.index')
  expect(routeFromPosts?.path).toBe('/blog')
  expect(routeFromPosts?.params).toBe(undefined)
  expect(routeFromPosts?.methods).toStrictEqual(['GET'])

  expect(routeFromPostsSlug?.name).toBe('posts.show')
  expect(routeFromPostsSlug?.path).toBe('/blog/{post_slug}')
  expect(routeFromPostsSlug?.params).toEqual({ post_slug: 'string' })
  expect(routeFromPostsSlug?.methods).toStrictEqual(['GET'])
})

it('can get route', async () => {
  const list = LaravelRouter.create(Routes)

  const home = list.getRouteLink('home')
  const postsSlug = list.getRouteLink('posts.show')

  expect(home?.name).toBe('home')
  expect(home?.path).toBe('/')
  expect(home?.params).toBe(undefined)
  expect(home?.methods).toStrictEqual(['GET'])

  expect(postsSlug?.name).toBe('posts.show')
  expect(postsSlug?.path).toBe('/blog/{post_slug}')
  expect(postsSlug?.params).toEqual({ post_slug: 'string' })
  expect(postsSlug?.methods).toStrictEqual(['GET'])
})
