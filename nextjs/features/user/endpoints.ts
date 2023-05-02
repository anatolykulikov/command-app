import { requestRunner, ResponseType } from '../../helpers/requestRunner'
import { Profile } from './types'

export const profileEndpoints = {
  current: async (): Promise<ResponseType<Profile>> => {
    return await requestRunner('user/current', 'get')
  },
}
