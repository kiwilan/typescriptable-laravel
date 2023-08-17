import { expect, it } from 'vitest'
import { Route } from '../src/methods'
import { Routes } from '../routes'

it('can use route item', async () => {
  const item = Route.make('feeds.show', Routes)

  expect(item).not.toBe(undefined)
  expect(item.getPath()).toBe('/feeds/?')
})
