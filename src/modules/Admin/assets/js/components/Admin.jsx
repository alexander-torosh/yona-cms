import React, { Component } from 'react'
import ReactDOM from 'react-dom'

class Admin extends Component {
  render() {
    return (
      <div>Admin Dashboard</div>
    )
  }
}

export default Admin

document.addEventListener('DOMContentLoaded', () => {
  const element = document.getElementById('admin-root');
  if (element) {
    ReactDOM.render(
      <Admin />,
      element,
    );
  }
});