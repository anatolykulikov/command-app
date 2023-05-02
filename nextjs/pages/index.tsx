import React from 'react'
import { NextPage } from 'next'
import { useProfile } from '../features/user/hook'
import { useDispatch } from 'react-redux'
import { setProfile } from '../features/user/model'
import { profileEndpoints } from '../features/user/endpoints'

const Home: NextPage = (): JSX.Element => {
  const profile = useProfile()
  const dispatch = useDispatch()

  const update = async () => {
    console.clear()
    console.log('update')

    try {
      const profile = await profileEndpoints.current()
      console.log('profile', profile)
      dispatch(setProfile(profile.data))
    } catch (e) {
      console.log('Ошибка', e)
    }
  }

  console.clear()
  console.log(profile)

  return (
    <div>
      Its home page <button onClick={() => update()}>click</button>
    </div>
  )
}

export default Home
