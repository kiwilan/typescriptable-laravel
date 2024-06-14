import { expect, it } from 'vitest'
import { LaravelRouter } from '../src/methods'
import { Routes } from '../routes'

it('can use router', async () => {
  const list = LaravelRouter.create(Routes).getAllRoutes()

  expect(list).not.toBe(undefined)
  expect(list?.home.name).toBe('home')
})
