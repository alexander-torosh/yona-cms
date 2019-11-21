import React, {Component} from 'react'
import ReactDOM from 'react-dom'
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from 'react-router-dom'

import Header from './Layout/Header'
import Menu from './Layout/Menu'

import Index from './Dashboard/Index'

import './Dashboard.scss'

class Dashboard extends Component {
  render() {
    return (
      <Router>
        <div className="Dashboard">
          <Menu/>
          <div className="Dashboard__contents">
            <Header/>
            <div className="ui container">
              <Switch>
                <Route exact path="/dashboard" component={Index}/>
              </Switch>
            </div>
          </div>
        </div>
      </Router>
    )
  }
}

document.addEventListener('DOMContentLoaded', (event) => {
  ReactDOM.render(<Dashboard/>, document.getElementById('dashboard-root'))
})