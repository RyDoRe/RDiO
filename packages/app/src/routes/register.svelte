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

  // function for registering a new user
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

    // check if the request was successful
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

  .loginButton {
    font-weight: 200;
    color: rgb(216, 213, 29);
  }

  .question {
    margin-top: 1em;
  }
</style>

<svelte:head>
	<title>RDIO - Register</title>
</svelte:head>

<h1>Register</h1>

<!--
  form for submitting the register information
-->
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

<p class="question" >Already registered?</p>
<a class="loginButton" href="/login">Login</a>
