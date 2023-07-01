import { expect, it } from 'vitest'
import { RouteItem } from '../src/methods'
import { Routes } from '../routes'

it('can use route item', async () => {
  const item = RouteItem.make('feeds/{feed_slug}', Routes)

  expect(item).not.toBe(undefined)
})
