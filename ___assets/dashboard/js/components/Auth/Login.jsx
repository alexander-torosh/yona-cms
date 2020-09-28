import React, { Component } from 'react'
import { Button, Form, Grid, Header, Image, Message, Segment } from 'semantic-ui-react'
import Fingerprint2 from 'fingerprintjs2'

import LoginService from './LoginService'

import './Login.scss'

class Login extends Component {
  constructor(props) {
    super(props)

    this.state = {
      email: '',
      password: '',
      csrfToken: '',
      fingerprintHash: '',

      requesting: false,
      error: '',
    }

    this.requestCsrfToken = this.requestCsrfToken.bind(this)
    this.requestAuthEndpoint = this.requestAuthEndpoint.bind(this)

    this.handleEmailChange = this.handleEmailChange.bind(this)
    this.handlePasswordChange = this.handlePasswordChange.bind(this)
    this.handleLoginFormSubmit = this.handleLoginFormSubmit.bind(this)
  }

  componentDidMount() {
    const requestCsrfFunction = async () => {
      const fingerprintHash = await this.getClientFingerprint()
      this.setState({ fingerprintHash })
      await this.requestCsrfToken(fingerprintHash)
    }

    if (window.requestIdleCallback) {
      requestIdleCallback(requestCsrfFunction)
    } else {
      setTimeout(requestCsrfFunction, 500)
    }
  }

  getClientFingerprint() {
    return new Promise((resolve, reject) => {
      const options = {}
      Fingerprint2
        .getPromise(options)
        .then((components) => {
          const values = components.map((component) => { return component.value })
          const fingerprintHash = Fingerprint2.x64hash128(values.join(''), 31)
          console.log('fingerprintHash:', fingerprintHash)
          resolve(fingerprintHash)
        })
    })
  }

  async requestCsrfToken(fingerprintHash) {
    try {
      const csrfToken = await LoginService.requestCsrfProtectionToken(fingerprintHash)
      this.setState({
        csrfToken
      })
    } catch (err) {
      console.log('error:', err.message)
      this.setState({
        error: err.message
      })
    }
  }

  async requestAuthEndpoint() {
    const { email, password, csrfToken, fingerprintHash } = this.state
    try {
      await LoginService.requestAuthEndpoint({
        email,
        password,
        csrfToken,
        fingerprintHash,
      })

      this.setState({
        requesting: false,
      })

    } catch (err) {
      console.log('error:', err.message)
      this.setState({
        requesting: false,
        error: err.message,
      })
    }
  }

  handleEmailChange(e) {
    const { target: { value } } = e
    this.setState({ email: value })
  }

  handlePasswordChange(e) {
    const { target: { value } } = e
    this.setState({ password: value })
  }

  handleLoginFormSubmit() {
    console.log('submit')
    const { email, password, csrfToken, requesting } = this.state
    console.log(email, password)
    if (false === requesting) {
      if (email.length > 0 && password.length > 0) {
        this.setState({ requesting: true }, this.requestAuthEndpoint)
      }
    } else {
      console.log('requesting:', requesting)
    }
  }

  render() {
    const { email, password, csrfToken, requesting, error } = this.state
    const loginButtonDisabled = (csrfToken === '' || requesting === true)

    return (
      <Grid textAlign='center' style={{ height: '100vh' }} verticalAlign='middle'>
        <Grid.Column style={{ maxWidth: 450 }}>
          <Header as='h2' color='teal' textAlign='center'>
            <Image src='/logo.png' /> Log-in to your account
          </Header>
          <Form
            className='attached stacked'
            size='large'
            onSubmit={this.handleLoginFormSubmit}>
            <Segment stacked>
              <Form.Input
                fluid icon='user'
                iconPosition='left'
                placeholder='E-mail address'
                type='email'
                required={true}
                value={email}
                onChange={this.handleEmailChange}
              />
              <Form.Input
                fluid
                icon='lock'
                iconPosition='left'
                placeholder='Password'
                type='password'
                required={true}
                value={password}
                onChange={this.handlePasswordChange}
              />

              <Button
                color='teal'
                fluid size='large'
                disabled={loginButtonDisabled}
              >
                Login
              </Button>
            </Segment>
          </Form>
          {error !== '' &&
            <Message attached='bottom' error>
              {error}
            </Message>
          }
          <Message>
            Forgot password? <a href='#'>Recover</a>
          </Message>
        </Grid.Column>
      </Grid>
    )
  }
}

export default Login