import { promises as fs } from 'node:fs'

export async function execute(command: string): Promise<void> {
  if (isProduction())
    return

  const { exec } = await import('node:child_process')

  exec(command, (error) => {
    if (error) {
      console.error(`exec error: ${error}`)
      return
    }
    // eslint-disable-next-line no-console
    console.log(`${command} ready!`)
  })
}

export function isProduction(): boolean {
  return process.env.NODE_ENV === 'production'
}

export async function write(path: string, content: string): Promise<void> {
  await fs.writeFile(path, content)
}
