<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (authenticated) {
      this.redirect(302, '')
    }
  }
</script>

<script>
  import Input from '../components/Input.svelte'
  import Button from '../components/Button.svelte'

  import { goto, stores } from '@sapper/app'
  import { post } from 'api'

  const { session } = stores()

  let error

  //function for logging in a valid user
  async function login (event) {
    const response = await post('auth/login', {
      email: event.target.email.value,
      password: event.target.password.value
    })

    const json = await response.json()

    //check if request was valid and redirecting him accordingly
    if (response.status === 200) {
      session.set({
        authenticated: true,
        username: json.name,
        role: json.role
      })
      goto('/')
    } else {
      if (json.error) {
        error = json.error
      } else {
        error = Object.keys(json).map(key => {
          return json[key].join('')
        }).join(' ')
      }
    }
  }
</script>

<style>
  form {
    display: flex;
    flex-direction: column;
    max-width: 350px;
  }

  .error {
    color: red;
  }
</style>

<svelte:head>
	<title>RDIO - Login</title>
</svelte:head>

<h1>Login</h1>


<!--
  form for login information
-->
<form on:submit|preventDefault={login}>
  <Input name="email" placeholder="Email"/>
  <Input type="password" name="password" placeholder="Password"/>
  <Button type="submit">Login</Button>
  {#if error}
    <p class="error">Error: {error}</p>
  {/if}
</form>

<a href="/register">Register</a>
