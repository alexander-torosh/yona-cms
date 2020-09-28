import React, { Component } from 'react'
import ReactDOM from 'react-dom'

import './Homepage.scss'

class Homepage extends Component {
    render() {
        return (
            <div className="Homepage">
                <h1>Yona CMS</h1>
                <p>Welcome to Yona CMS Homepage. This page renders by React.js.</p>
            </div>
        )
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    ReactDOM.render(<Homepage />, document.getElementById('homepage-root'))
})