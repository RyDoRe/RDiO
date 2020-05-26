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

  import { goto } from '@sapper/app'
  import { post } from 'api'

  let error

  async function register (event) {
    const {
      name,
      email,
      password,
      passwordConfirmation
    } = event.target

    const response = await post('auth/register', {
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })

    if (response.status === 201) {
      goto('/login')
    } else {
      const json = await response.json()
      if (json.error) {
        error = json.error
      } else {
        error = Object.keys(json).map(key => {
          return json[key].join('')
        }).join(' ')
        console.log(error)
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

<h1>Register</h1>

<form on:submit|preventDefault={register}>
  <Input type="text" name="name" placeholder="Name"/>
  <Input type="text" name="email" placeholder="Email"/>
  <Input type="password" name="password" placeholder="Password"/>
  <Input type="password" name="passwordConfirmation" placeholder="Password Confirmation"/>
  <Button type="submit">Register</Button>
  {#if error}
    <p class="error">Error: {error}</p>
  {/if}
</form>

<a href="/login">Login</a>
