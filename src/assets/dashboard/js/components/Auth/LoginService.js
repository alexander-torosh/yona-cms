import request from 'superagent'

class LoginService {
    static requestCsrfProtectionToken(fingerprintHash) {
        return new Promise((resolve, reject) => {
            request
                .post('/api/auth/csrf')
                .send({ fingerprintHash })
                .accept('application/json')
                .then((res) => {
                    const { body } = res
                    const { csrfToken } = body
                    if (csrfToken) {
                        return resolve(csrfToken)
                    } else {
                        return reject(new Error('Security token request failed.'))
                    }
                })
                .catch((err) => {
                    const { response: { body: { message } } } = err
                    return reject(new Error(message))
                })
        })
    }

    static requestAuthEndpoint({ email, password, csrfToken, fingerprintHash }) {
        return new Promise((resolve, reject) => {
            request
                .post('/api/auth')
                .send({
                    email,
                    password,
                    csrfToken,
                    fingerprintHash,
                })
                .accept('application/json')
                .then((res) => {
                    const { body } = res
                    const { success } = body
                    if (success === true) {
                        return resolve()
                    } else {
                        return reject(new Error('Authentication failed. Try again.'))
                    }
                })
                .catch((err) => {
                    const { response: { body: { message } } } = err
                    return reject(new Error(message))
                })
        })

    }
}

export default LoginService