export async function execute(command: string): Promise<void> {
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

export async function write(path: string, content: string): Promise<void> {
  const { writeFile } = await import('node:fs/promises')
  await writeFile(path, content)
}
