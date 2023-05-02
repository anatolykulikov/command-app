type RequestTypeMethods = 'get' | 'post' | 'put' | 'delete'

export type ResponseType<T = unknown> = {
  status: 'ok' | 'error'
  data: T
}

export const requestRunner = async <
  T = ResponseType,
  D = Record<string, unknown>,
>(
  src: string,
  method: RequestTypeMethods,
  body?: D,
  repeats = 0,
): Promise<T> => {
  const requestBody = body
    ? {
        method,
        body: JSON.stringify(body),
      }
    : null

  try {
    const req = await fetch(`http://localhost:9095/api/${src}`, requestBody)
    return req.json()
  } catch (e) {
    if (repeats > 0) return requestRunner(src, method, body, repeats - 1)
    throw e
  }
}
