import { createSlice, PayloadAction } from '@reduxjs/toolkit'
import { InitialProfile } from './constants'
import { Profile } from './types'

export const profileSlice = createSlice({
  name: 'profile',
  initialState: InitialProfile,
  reducers: {
    setProfile: (state: Profile, action: PayloadAction<Profile>) => {
      return { ...state, ...action.payload }
    },
    clearProfile: (state) => {
      return { ...state, ...InitialProfile }
    },
  },
})

export const { setProfile, clearProfile } = profileSlice.actions
export default profileSlice.reducer
