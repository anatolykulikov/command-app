import React from 'react'
import { ButtonProps } from './types'
import { ButtonElement } from './styles'

export const Button: React.FunctionComponent<ButtonProps> = ({
  text,
  type = 'button',
  onClick = null,
  disabled,
}): JSX.Element => {
  return (
    <ButtonElement
      type={type}
      onClick={
        onClick
          ? () => onClick()
          : () => {
              return
            }
      }
      disabled={disabled}
    >
      <span>{text}</span>
    </ButtonElement>
  )
}
