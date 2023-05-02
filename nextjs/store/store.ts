import { combineReducers, configureStore } from '@reduxjs/toolkit'
import { profileSlice } from '../features/user/model'

export const store = configureStore({
  reducer: combineReducers({
    profile: profileSlice.reducer,
  }),
  devTools: true,
})

export type RootState = ReturnType<typeof store.getState>
export type AppDispatch = typeof store.dispatch
