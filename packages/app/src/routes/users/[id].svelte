<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>

<script>
  import Input from '../../components/Input.svelte'
  import Button from '../../components/Button.svelte'

  import { get, put } from 'api'
  import { stores, goto } from '@sapper/app'
  import { onMount } from 'svelte'

  const { page } = stores()
  const { id } = $page.params

  let user
  let error

  onMount(async () => {
    const response = await get(`users/${id}`)

    const json = await response.json()

    if (response.status === 200) {
      user = json
    } else {
      goto(404, `users/${id}`)
    }
  })

  //function call for updatting the user information
  async function updateUser (event) {
    const response = await put(`users/${id}`, {
      name: event.target.name.value,
      email: event.target.email.value,
      role: event.target.role.value,
      current_password: event.target.current_password.value,
      password: event.target.password.value,
      password_confirmation: event.target.password_confirmation.value
    })

    const json = await response.json()

    //check if function call was successful
    if (response.status === 200) {
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

<!--
  form for updatting the given user information
-->
{#if user}
  <form on:submit|preventDefault={updateUser}>
    <label for="name">Username: </label>
    <Input id="name" placeholder="Name" value={user.name} name="name" />
    <label for="email">Email: </label>
    <Input id="email" placeholder="Email" value={user.email} name="email" />
    <Input id="current_password" type="password" placeholder="Old Password" name="current_password" />
    <Input id="password" type="password" placeholder="Password" name="password" />
    <Input id="password_confirmation" type="password" placeholder="Password Confirmation" name="password_confiramtion" />
    <label for="role">Role</label>
    <select id="role" name="role" value={user.role}>
        <option value="user">User</option>
        <option value="broadcaster">Broadcaster</option>
        <option value="admin">Admin</option>
    </select>
    <Button type="submit">Save</Button>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </form>
{/if}
