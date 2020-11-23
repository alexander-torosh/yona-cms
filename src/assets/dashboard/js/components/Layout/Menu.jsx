import React, {Component} from 'react'
import { Menu as SemanticMenu } from 'semantic-ui-react'

import './Menu.scss'

const { Item } = SemanticMenu

class Menu extends Component {
  constructor(props) {
    super(props)

    this.state = { activeItem: 'Dashboard' }

    this.handleItemClick = this.handleItemClick.bind(this)
  }

  handleItemClick(e, { name }) {
    this.setState({ activeItem: name })
  }

  render() {
    const { activeItem } = this.state

    return (
      <SemanticMenu inverted vertical>
        <div className="title">Yona CMS</div>
        <Item
          name='Dashboard'
          active={activeItem === 'Dashboard'}
          onClick={this.handleItemClick}
        />
        <Item
          name='Blog'
          active={activeItem === 'Blog'}
          onClick={this.handleItemClick}
        />
        <Item
          name='Users'
          active={activeItem === 'Users'}
          onClick={this.handleItemClick}
        />
      </SemanticMenu>
    )
  }
}

export default Menu