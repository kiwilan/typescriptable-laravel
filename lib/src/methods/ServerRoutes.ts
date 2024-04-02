import fs from 'node:fs'

export class ServerRoutes {
  protected constructor(
    protected path = './resources/js/routes.ts',
    public routes = {} as Record<App.Route.Name, App.Route.Link>,
  ) {}

  public static create(): ServerRoutes {
    const self = new ServerRoutes()

    if (!ServerRoutes.isClient()) {
      const routes = self.serverRoutes()
      if (routes)
        self.routes = routes
    }
    else {
      const routes = window.Routes
      if (routes)
        self.routes = routes
    }

    return self
  }

  public static isClient() {
    const isClient = typeof window !== 'undefined'

    if (!isClient) {
      // console.error('`window` not found')
      return false
    }

    if (typeof window?.Routes === 'undefined') {
      console.error('`@kiwilan/typescriptable-laravel` error: `window.Routes` not found')
      return false
    }

    return true
  }

  public static getBaseURL(): string | undefined {
    const self = new ServerRoutes()
    const contents = self.getRoutesFileContents()

    if (!contents)
      return undefined

    // regex for `const appUrl = 'http://';`
    const regex = /const appUrl = '(.*)';/g
    const match = contents.match(regex)

    if (!match)
      return undefined

    const url = match[0].replace(/const appUrl = '/g, '').replace(/';/g, '')

    return url
  }

  /**
   * Check if file exists.
   * @param path File path.
   * @returns `true` if file exists, `false` otherwise.
   */
  private checkFileExists(path: string) {
    return fs.existsSync(path)
  }

  private getRoutesFileContents(): string | undefined {
    if (!this.path)
      return undefined

    try {
      const exists = this.checkFileExists(this.path)

      if (!exists)
        return undefined

      const contents = fs.readFileSync(this.path, { encoding: 'utf-8' })
      const contentsAsString = contents.toString()

      return contentsAsString
    }
    catch (error) {
      console.error('`@kiwilan/typescriptable-laravel` error: error reading file `routes.ts`', error)

      return undefined
    }
  }

  /**
   * List all files from the given directory.
   */
  private serverRoutes(): Record<App.Route.Name, App.Route.Link> | undefined {
    if (typeof window !== 'undefined')
      return undefined

    try {
      const fileContents = this.getRoutesFileContents()

      if (!fileContents)
        return undefined

      const regex = /const\s+Routes\s*:(.*)(?=declare global)/s
      const match = fileContents.match(regex)
      if (match) {
        const routesStr = match[0]
        let replaced = routesStr.replace(/const Routes: Record<App\.Route\.Name,\s*App\.Route\.Link>\s*=\s*{/, '{')
        replaced = replaced.replaceAll(/name:/g, '"name":')
        replaced = replaced.replaceAll(/path:/g, '"path":')
        replaced = replaced.replaceAll(/params:/g, '"params":')
        replaced = replaced.replaceAll(/methods:/g, '"methods":')
        replaced = replaced.replaceAll(/undefined/g, 'null')
        replaced = replaced.replaceAll(/\b(\w+):/g, '"$1":')
        replaced = replaced.replaceAll(/,\n\s*}/g, '\n}')
        replaced = replaced.replaceAll(/],/g, ']')
        replaced = replaced.replaceAll(/'/g, '"')

        const routes = JSON.parse(replaced.trim())
        return routes
      }

      return undefined
    }
    catch (error) {
      return undefined
    }
  }
}
