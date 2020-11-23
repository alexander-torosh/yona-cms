import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from 'react-router-dom'

import Login from './Auth/Login'

import './Auth.scss'

class Auth extends Component {
  render() {
    return (
      <Router>
        <div className="Auth">
          <Switch>
            <Route exact path="/dashboard/auth/login" component={Login}/>
          </Switch>
        </div>
      </Router>
    )
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  const element = document.getElementById('auth-root')
  if (element) {
    ReactDOM.render(<Auth />, element)
  }
})