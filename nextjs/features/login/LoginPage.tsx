import React, { useState } from 'react'
import { LoginFormBody, LoginPageForm } from './styles'
import { Input } from '../../ui/Inputs/Input'
import { Button } from '../../ui/Buttons/Button'
import { authEndpoints } from './endpoints'
import { useDispatch } from 'react-redux'
import { setProfile } from '../user/model'

export const LoginPage: React.FunctionComponent = (): JSX.Element => {
  const dispatch = useDispatch()
  const [loading, setLoading] = useState<boolean>(false)
  const [lg, setLg] = useState<string>('')
  const [ps, setPs] = useState<string>('')

  const disabledHandler = () => {
    return lg.length < 2 || ps.length < 4
  }

  const authRequest = async () => {
    console.clear()
    console.log('authRequest')
    if (disabledHandler()) return false

    setLoading(true)

    try {
      const auth = await authEndpoints.login({ lg, ps })
      console.log('auth', auth)

      if (auth.status === 'ok') {
        dispatch(setProfile(auth.data))
      }
    } catch (e) {
      console.log('err', e)
    } finally {
      setLoading(false)
    }
  }

  return (
    <LoginPageForm>
      <LoginFormBody>
        <Input
          label={'Логин'}
          placeholder={'Введите логин'}
          value={lg}
          onInput={(e) => setLg(e.target.value)}
          disabled={loading}
        />
        <Input
          label={'Пароль'}
          placeholder={'Введите пароль'}
          value={ps}
          type={'password'}
          onInput={(e) => setPs(e.target.value)}
          disabled={loading}
        />
        <Button
          text={'Войти'}
          onClick={() => authRequest()}
          type={'submit'}
          disabled={disabledHandler()}
        />
      </LoginFormBody>
    </LoginPageForm>
  )
}
