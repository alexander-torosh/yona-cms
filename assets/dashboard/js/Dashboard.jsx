import React, { Component } from 'react'
import ReactDOM from 'react-dom'

class Dashboard extends Component {
  render() {
    return (
      <div>
        <h1>Dashboard - Yona CMS</h1>
      </div>
    )
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  ReactDOM.render(<Dashboard />, document.getElementById('root'))
})