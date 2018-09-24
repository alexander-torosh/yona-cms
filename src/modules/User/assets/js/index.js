import React, { Fragment } from 'react'
import ReactDOM from 'react-dom'
import Loadable from 'react-loadable'

document.addEventListener('DOMContentLoaded', () => {
  // User Admin
  const userAdminRoot = document.getElementById('user-admin-root')
  if (userAdminRoot) {
    const UserAdminLoadable = Loadable({
      loader: () => import('./components/Admin'),
      loading() {
        return <Fragment />
      }
    })

    ReactDOM.render(
      <UserAdminLoadable />,
      userAdminRoot
    )
  }
  
  // User Index
  const userIndexRoot = document.getElementById('user-index-root')
  if (userIndexRoot) {
    const UserIndexLoadable = Loadable({
      loader: () => import('./components/Index'),
      loading() {
        return <Fragment />
      }
    })

    ReactDOM.render(
      <UserIndexLoadable />,
      userIndexRoot
    )
  }

  // User Login
  const userLoginRoot = document.getElementById('user-login-root')
  if (userLoginRoot) {
    console.log(userLoginRoot)
    const UserLoginLoadable = Loadable({
      loader: () => import('./components/Login'),
      loading() {
        return <Fragment />
      }
    })

    ReactDOM.render(
      <UserLoginLoadable />,
      userLoginRoot
    )
  }
})