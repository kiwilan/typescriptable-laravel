import { expect, it } from 'vitest'
import { LaravelRoute } from '../src/methods'
import { Routes } from '../routes'

it('can use route item', async () => {
  const item = LaravelRoute.create('feeds.show', Routes)

  expect(item).not.toBe(undefined)
  expect(item.getPath()).toBe('/feeds/?')
})
