import React, {Component} from 'react'

import './Menu.scss'

class Menu extends Component {
  render() {
    return (
      <div className="Menu">
        <div className="title">Yona CMS</div>
        <div className="ui inverted vertical menu">
          <a className="active item">
            Home
          </a>
          <a className="item">
            Messages
          </a>
          <a className="item">
            Friends
          </a>
        </div>
      </div>
    )
  }
}

export default Menu