<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>

<script>
  import IconButton from '../../components/IconButton.svelte'
  import FloatingButton from '../../components/FloatingButton.svelte'
  import Input from '../../components/Input.svelte'
  import ListItem from '../../components/ListItem.svelte'
  import ListItemText from '../../components/ListItemText.svelte'
  import Dialog from '../../components/Dialog.svelte'
  import Icon from 'svelte-awesome/components/Icon.svelte'

  import { edit, trash, plus } from 'svelte-awesome/icons'

  import { get, post, put, del } from 'api'
  import { goto } from '@sapper/app'
  import { onMount } from 'svelte'
  import { flip } from 'svelte/animate'

  let playlists
  // Visibilty of dialogs
  let showAddDialog = false
  let showEditDialog = false
  let showDeleteDialog = false

  // Reference to input elements
  let inputRef
  // Focus the input element
  $: inputRef && inputRef.focus()

  let id
  let index
  let name = ''
  let error

  // Search
  let searchTerm = ''
  let filteredPlaylists
  // Filter playlists based on the searchTerm
  $: playlists && (filteredPlaylists = playlists.filter(playlist => playlist.name.toLowerCase().includes(searchTerm.toLowerCase())))

  async function fetchPlaylists () {
    // Fetch playlist
    const response = await get('playlists')
    playlists = await response.json()
  }

  onMount(() => fetchPlaylists())

  // Add a new playlist
  async function addPlaylist () {
    const response = await post('playlists', {
      name
    })

    const json = await response.json()

    if (response.status === 201) {
      fetchPlaylists()
      handleClose()
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

  // Close the dialog and reset input values
  function handleClose () {
    showAddDialog = false
    showEditDialog = false
    showDeleteDialog = false
    name = ''
    error = null
  }

  // Open delete dialog and store playlist data for confirmation
  function openDeleteDialog (event, playlistId, playlistIndex) {
    event.stopPropagation()
    showDeleteDialog = true
    id = playlistId
    index = playlistIndex
  }

  // Delete a playlist
  async function deletePlaylist () {
    const response = await del(`playlists/${id}`)

    if (response.status === 200) {
      playlists = [...playlists.slice(0, index), ...playlists.slice(index + 1)]
      handleClose()
    }
  }

  // Open edit dialog and store playlist data for confirmation
  function openEditDialog (event, playlistId, playlistName, playlistIndex) {
    event.stopPropagation()
    showEditDialog = true
    id = playlistId
    name = playlistName
    index = playlistIndex
  }

  // Edit a playlist
  async function editPlaylist () {
    const response = await put(`playlists/${id}`, {
      name
    })

    const json = await response.json()

    if (response.status === 200) {
      // Replace the name in playlists
      playlists = playlists.map((playlist, playlistIndex) => {
        if (index !== playlistIndex) {
          return playlist
        }

        return {
          ...playlist,
          name: json.name
        }
      })
      handleClose()
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
  :global(.addbutton) {
    position: fixed;
    right: 2rem;
    bottom: 4rem;
  }

  .error {
    color: red;
  }
</style>

<h1>Playlists</h1>

{#if filteredPlaylists}
  <Input placeholder="Search..." bind:value={searchTerm} />
  {#each filteredPlaylists as playlist, playlistIndex (playlist.id)}
    <div animate:flip>
      <ListItem on:click={() => goto(`playlists/${playlist.id}`)}>
        <ListItemText>{playlist.name}</ListItemText>
        <span style="color: white;">{playlist.songs_count} Song(s)</span>
        <IconButton icon={edit} on:click={e => openEditDialog(e, playlist.id, playlist.name, playlistIndex) }/>
        <IconButton icon={trash} on:click={e => openDeleteDialog(e, playlist.id, playlistIndex)} />
      </ListItem>
    </div>
  {/each}
{/if}

{#if showAddDialog}
  <Dialog onClose={handleClose} onConfirm={addPlaylist} title="Add a new playlist">
    <Input placeholder="Name" bind:value={name} bind:ref={inputRef} on:enter={addPlaylist} />
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}

{#if showEditDialog}
  <Dialog onClose={handleClose} onConfirm={editPlaylist} title="Edit Playlist">
    <Input placeholder="Name" bind:value={name} bind:ref={inputRef} on:enter={editPlaylist} />
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}

{#if showDeleteDialog}
  <Dialog onClose={handleClose} onConfirm={deletePlaylist} title="Delete playlist">
    <p>Are you sure you want to delete this playlist?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}

<FloatingButton class="addbutton" on:click={() => { showAddDialog = true }}>
  <Icon data={plus} />
</FloatingButton>
