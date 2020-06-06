<script context="module">
  export function preload (page, session) {
    const { authenticated, role } = session

    if (!authenticated || role !== 'admin') {
      this.redirect(302, '')
    }
  }
</script>

<script>
  import IconButton from '../../components/IconButton.svelte'
  import ListItem from '../../components/ListItem.svelte'
  import ListItemText from '../../components/ListItemText.svelte'
  import Dialog from '../../components/Dialog.svelte'

  import { trash } from 'svelte-awesome/icons'

  import { get, del } from 'api'
  import { goto } from '@sapper/app'
  import { onMount } from 'svelte'
  import { flip } from 'svelte/animate'

  // Visibilty of dialogs
  let showDeleteDialog = false

  let users
  let id
  let index
  let error

  onMount(async () => {
    const response = await get('users')
    const json = await response.json()
    users = json
  })

  // Close the dialog and reset input values
  function handleClose () {
    showDeleteDialog = false
    error = null
  }

  //open the delete user dialog
  function openDeleteDialog (event, userId, userIndex) {
    event.stopPropagation()
    showDeleteDialog = true
    id = userId
    index = userIndex
  }

  //function call to delete the user
  async function deleteUser () {
    const response = await del(`users/${id}`)

    //check if the delete was successful and closes the dialog if so
    if (response.status === 200) {
      users = [...users.slice(0, index), ...users.slice(index + 1)]
      handleClose()
    }
  }
</script>

<style>
  .error {
    color: red;
  }
</style>

<h1>Users</h1>

<!--
  show all users
-->
{#if users}
  {#each users as user, userIndex (user.id)}
    <div animate:flip>
      <ListItem on:click={() => goto(`users/${user.id}`)}>
        <ListItemText>{user.name}</ListItemText>
        <IconButton icon={trash} on:click={e => openDeleteDialog(e, user.id, userIndex)} />
      </ListItem>
    </div>
  {/each}
{/if}

{#if showDeleteDialog}
  <Dialog onClose={handleClose} onConfirm={deleteUser} title="Delete user">
    <p>Are you sure you want to delete this user?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}
