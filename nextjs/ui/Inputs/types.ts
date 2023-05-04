export type InputTypes = {
  value: string
  onInput: (e) => void
  name?: string
  type?: 'text' | 'password' | 'number' | 'email' | 'search' | 'tel' | 'url'
  label?: string
  smallText?: string
  placeholder?: string
  disabled?: boolean
}
