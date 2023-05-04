import { AppProps } from 'next/dist/shared/lib/router/router'
import React, { useEffect } from 'react'
import { Provider } from 'react-redux'
import Head from 'next/head'
import { store } from '../store/store'
import { profileEndpoints } from '../features/user/endpoints'
import '../ui/global.css'

const MyApp = ({ Component, pageProps, router }: AppProps): JSX.Element => {
  // console.clear()
  // console.log('============== MyApp ==============')
  // console.log(pageProps)
  // console.log('router', router)

  useEffect(() => {
    if (!pageProps.auth && router.pathname !== '/login') {
      console.log('На страницу логина')
      router.push('/login')
    }
  }, [pageProps.auth, router])

  if (!pageProps.auth && router.pathname !== '/login') return <></>

  return (
    <Provider store={store}>
      <Head>
        <title>NextJS App From Scratch</title>
      </Head>
      <Component {...pageProps.props} />
    </Provider>
  )
}

MyApp.getInitialProps = async (props) => {
  const { ctx, Component } = props
  const pageProps = {
    auth: null,
    props: null,
  }

  if (ctx.pathname !== '/login') {
    const user = await profileEndpoints.current()
    if (user.status === 'ok') return { pageProps }

    pageProps.auth = user.data

    if (Component.getServerSideProps)
      pageProps.props = await Component.getServerSideProps(ctx)
  }

  return { pageProps }
}

export default MyApp
