import type { HttpResponse } from './HttpResponse'

export class HttpDownload {
  private constructor(
    private response: HttpResponse,
    private filename?: string,
  ) {}

  /**
   * Create a new download instance.
   */
  public static async create(response: HttpResponse, forceFilename?: string): Promise<HttpDownload> {
    const self = new HttpDownload(response, forceFilename)
    if (!forceFilename)
      self.filename = self.findName(response)

    return self
  }

  /**
   * Trigger a download from a URL.
   */
  public direct(url: string | undefined, filename?: string): void {
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

  /**
   * Download the response as a Blob.
   */
  public async blob(): Promise<void> {
    const blob = await this.response.getBody<Blob>('blob')
    if (!blob) {
      console.warn('No Blob provided to download')
      return
    }
    return this.fromBlob(blob, this.filename)
  }

  /**
   * Download the response as an ArrayBuffer.
   */
  public async arrayBuffer(): Promise<void> {
    const arrayBuffer = await this.response.getBody<ArrayBuffer>('arrayBuffer')
    if (!arrayBuffer) {
      console.warn('No ArrayBuffer provided to download')
      return
    }
    return this.fromArrayBuffer(arrayBuffer, this.filename)
  }

  /**
   * Download the response as a Blob.
   */
  private fromBlob(blob: Blob, filename?: string): void {
    const url = URL.createObjectURL(blob) // Create a URL for the Blob
    this.direct(url, filename)

    URL.revokeObjectURL(url) // Revoke the Blob URL to free up memory
  }

  /**
   * Download the response as an ArrayBuffer.
   */
  private fromArrayBuffer(arrayBuffer: ArrayBuffer, filename?: string): void {
    const blob = new Blob([arrayBuffer])
    this.fromBlob(blob, filename)
  }

  /**
   * Find the filename from the response headers.
   */
  private findName(response: HttpResponse): string | undefined {
    const name = response.getHeader('Content-Disposition') ?? undefined
    if (name)
      return name.split('filename=')[1]

    return undefined
  }
}
