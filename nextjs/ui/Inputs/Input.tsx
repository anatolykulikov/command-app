import React from 'react'
import { InputTypes } from './types'
import { BaseInput, InputContainer, InputWrapper } from './styles'

export const Input: React.FunctionComponent<InputTypes> = ({
  value,
  onInput,
  name = null,
  type = 'text',
  label = null,
  smallText = null,
  placeholder = null,
  disabled = false,
}): JSX.Element => {
  return (
    <InputWrapper>
      {label && <span>{label}</span>}
      <InputContainer>
        <BaseInput
          name={name}
          value={value}
          onInput={(event) => onInput(event)}
          type={type}
          placeholder={placeholder}
          disabled={disabled}
        />
      </InputContainer>
      {smallText && <small>{smallText}</small>}
    </InputWrapper>
  )
}
