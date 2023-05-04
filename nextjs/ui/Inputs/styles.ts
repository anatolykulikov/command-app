import { styled } from '@linaria/react'

export const InputWrapper = styled.label`
  width: 100%;

  & > span {
    display: block;
    margin: 0 0 4px;
    font-size: 16px;
    line-height: 20px;
  }

  & > small {
    display: block;
    margin: 4px 0 0;
    font-size: 14px;
    line-height: 18px;
  }
`

export const InputContainer = styled.div`
  display: grid;
  grid-gap: 8px;
  padding: 8px;
  border: #e0e0e0 solid 1px;
  border-radius: 4px;
`

export const BaseInput = styled.input`
  display: block;
  width: 100%;
  border: none;
  outline: none;
`
