<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>

<script>
  import Input from '../components/Input.svelte'
  import Button from '../components/Button.svelte'

  import { stores } from '@sapper/app'
  import { get, put } from 'api'
  import { onMount } from 'svelte'

  const { session } = stores()

  let user
  let error

  onMount(async () => {
    const response = await get('users/me')
    const json = await response.json()

    if (response.status === 200) {
      user = json
    }
  })

  // function for updating the user information
  async function updateUser (event) {
    const response = await put('users/me', {
      name: event.target.name.value,
      email: event.target.email.value,
      current_password: event.target.current_password.value,
      password: event.target.password.value,
      password_confirmation: event.target.password_confirmation.value
    })

    const json = await response.json()

    // check if the request was successful
    if (response.status === 200) {
      session.update(store => {
        store.username = json.name
        return store
      })
      error = null
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

  label {
    color: #fff;
  }

  .error {
    color: red;
  }
</style>

<svelte:head>
	<title>RDIO - User Profile</title>
</svelte:head>


{#if user}
  <!-- 
    form of the user information
  -->
  <form on:submit|preventDefault={updateUser}>
    <label for="name">Username: </label>
    <Input id="name" placeholder="Name" value={user.name} name="name" />
    <label for="email">Email: </label>
    <Input id="email" placeholder="Email" value={user.email} name="email" />
    <label for="current_password">Old Password: </label>
    <Input id="current_password" type="password" placeholder="Old Password" name="current_password" />
    <label for="password">Password: </label>
    <Input id="password" type="password" placeholder="Password" name="password" />
    <label for="password_confirmation">Password Confirmation: </label>
    <Input id="password_confirmation" type="password" placeholder="Password Confirmation" name="password_confiramtion" />
    <Button type="submit">Save</Button>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </form>
{/if}
