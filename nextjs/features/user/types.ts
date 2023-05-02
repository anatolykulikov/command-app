export type UserMeta = {
  [key: string]: string
}

export interface Profile {
  login: string
  role: string
  active: boolean
  meta: UserMeta[]
  tasks: []
  teams: []
  communities: []
  events: []
}
