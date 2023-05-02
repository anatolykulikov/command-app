import { AppProps } from 'next/dist/shared/lib/router/router'
import React from 'react'
import { Provider } from 'react-redux'
import Head from 'next/head'
import { store } from '../store/store'

const MyApp = ({ Component, pageProps }: AppProps): JSX.Element => {
  return (
    <Provider store={store}>
      <Head>
        <title>NextJS App From Scratch</title>
      </Head>
      <Component {...pageProps} />
    </Provider>
  )
}

export default MyApp
