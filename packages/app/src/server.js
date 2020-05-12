import sirv from 'sirv'
import polka from 'polka'
import compression from 'compression'
import * as sapper from '@sapper/server'
import jwt from 'jsonwebtoken'
import cookieParser from 'cookie-parser'

const { PORT, NODE_ENV } = process.env
const dev = NODE_ENV === 'development'

polka() // You can also use Express
  .use(
    compression({ threshold: 0 }),
    sirv('static', { dev }),
    cookieParser(),
    function (req, res, next) {
      const token = req.cookies.jwt
      const jwtPayload = token ? jwt.decode(token) : false

      return sapper.middleware({
        session: function () {
          return {
            authenticated: !!jwtPayload,
            username: jwtPayload.name || '',
            role: jwtPayload.role || ''
          }
        }
      })(req, res, next)
    }
  )
  .listen(PORT, err => {
    if (err) console.log('error', err)
  })
