import { requestRunner, ResponseType } from '../../helpers/requestRunner'
import { Profile } from '../user/types'
import { CredentialsAuthType } from './types'

export const authEndpoints = {
  login: async (
    credentials: CredentialsAuthType,
  ): Promise<ResponseType<Profile>> => {
    return await requestRunner('login', 'post', credentials)
  },
}
