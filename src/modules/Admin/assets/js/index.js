import React, { Fragment } from 'react'
import ReactDOM from 'react-dom'
import Loadable from 'react-loadable'

document.addEventListener('DOMContentLoaded', () => {
  const adminRoot = document.getElementById('admin-root')
  if (adminRoot) {
    const AdminLoadable = Loadable({
      loader: () => import('./components/Admin'),
      loading() {
        return <Fragment />
      }
    })

    ReactDOM.render(
      <AdminLoadable />,
      adminRoot
    )
  }
})