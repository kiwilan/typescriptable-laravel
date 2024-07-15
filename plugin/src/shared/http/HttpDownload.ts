import type { HttpResponse } from './HttpResponse'

export class HttpDownload {
  private constructor(
    private response: HttpResponse,
    private filename?: string,
  ) {}

  public static async create(response: HttpResponse, forceFilename?: string): Promise<HttpDownload> {
    const self = new HttpDownload(response, forceFilename)
    if (!forceFilename)
      self.filename = self.findName(response)

    return self
  }

  public triggerDownload(url: string | undefined, filename?: string): void {
    if (!url) {
      console.warn('No URL provided to download')
      return
    }

    const a = document.createElement('a')
    a.setAttribute('href', url)
    if (filename) {
      a.setAttribute('download', filename)
    }
    else {
      const name = url.split('/').pop()
      if (name)
        a.download = name
    }

    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
  }

  public async blob(): Promise<void> {
    const blob = await this.response.getBody<Blob>('blob')
    if (!blob) {
      console.warn('No Blob provided to download')
      return
    }
    return this.fromBlob(blob, this.filename)
  }

  public async arrayBuffer(): Promise<void> {
    const arrayBuffer = await this.response.getBody<ArrayBuffer>('arrayBuffer')
    if (!arrayBuffer) {
      console.warn('No ArrayBuffer provided to download')
      return
    }
    return this.fromArrayBuffer(arrayBuffer, this.filename)
  }

  private fromBlob(blob: Blob, filename?: string): void {
    const url = URL.createObjectURL(blob) // Create a URL for the Blob
    this.triggerDownload(url, filename)

    URL.revokeObjectURL(url) // Revoke the Blob URL to free up memory
  }

  private fromArrayBuffer(arrayBuffer: ArrayBuffer, filename?: string): void {
    const blob = new Blob([arrayBuffer])
    this.fromBlob(blob, filename)
  }

  private findName(response: HttpResponse): string | undefined {
    const name = response.getHeader('Content-Disposition') ?? undefined
    if (name)
      return name.split('filename=')[1]

    return undefined
  }
}
