import { useSelector } from 'react-redux'
import { RootState } from '../../store/store'
import { Profile } from './types'

export const useProfile = (): Profile => {
  return useSelector<RootState, Profile>((state) => state.profile)
}
