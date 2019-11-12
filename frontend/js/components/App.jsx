import React, { Component } from 'react'
import ReactDOM from 'react-dom'

class App extends Component {
  render() {
    return (
      <div>
        <h1>Yona CMS</h1>
      </div>
    )
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  ReactDOM.render(<App />, document.getElementById('root'))
})