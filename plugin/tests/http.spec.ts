import { useFetch } from '@/composables/useFetch'
import { HttpResponse } from '@/shared/http'
import { expect, expectTypeOf, it } from 'vitest'

const { http } = useFetch()

const API_POSTS = 'https://jsonplaceholder.typicode.com/posts'

interface POST {
  userId: number
  id: number
  title: string
  body: string
}

async function testGetAll(res: HttpResponse) {
  const body = await res.getBody<POST[]>()

  if (!body) {
    throw new Error('body is not undefined')
  }

  expect(res).not.toBe(undefined)
  expect(body?.length).toBe(100)
  expectTypeOf(body[0]).toMatchTypeOf<POST>()
}

it('can use get', async () => {
  let res = await HttpResponse.create(API_POSTS, 'GET')
  await testGetAll(res)

  res = await http.get(API_POSTS)
  await testGetAll(res)
})

it('can use post', async () => {
  const res = await HttpResponse.create(API_POSTS, 'POST')
  const body = await res.getBody<{ id: number }>()

  if (!body) {
    throw new Error('body is not undefined')
  }

  expect(res).not.toBe(undefined)
  expectTypeOf(body).toMatchTypeOf<{ id: number }>()
})
