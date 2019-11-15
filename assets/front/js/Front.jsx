import React, { Component } from 'react'
import ReactDOM from 'react-dom'

class Front extends Component {
  render() {
    return (
      <div>
        <h1>Front - Yona CMS</h1>
      </div>
    )
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  ReactDOM.render(<Front />, document.getElementById('root'))
})