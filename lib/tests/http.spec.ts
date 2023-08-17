import { expect, it } from 'vitest'
import { Router } from '../src/methods'
import { Routes } from '../routes'

it('can use router', async () => {
  const list = Router.make(Routes).getAllRoutes()

  expect(list).not.toBe(undefined)
  expect(list?.home.name).toBe('home')
})
