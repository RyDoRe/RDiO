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
  import ListItem from '../../components/ListItem.svelte'
  import ListItemText from '../../components/ListItemText.svelte'
  import Dialog from '../../components/Dialog.svelte'
  import IconButton from '../../components/IconButton.svelte'

  import { times } from 'svelte-awesome/icons'

  import { get, post, put, del } from 'api'
  import { stores, goto } from '@sapper/app'
  import { onMount } from 'svelte'
  import { flip } from 'svelte/animate'

  const { page } = stores()
  const { id } = $page.params

  let playlist
  // Visibilty of dialogs
  let showRemoveDialog = false
  let showCreateRadioDialog = false

  let name
  let description
  let genre

  let _id
  let index
  let error

  let hovering = false

  onMount(async () => {
    const response = await get(`playlists/${id}`)

    const json = await response.json()

    if (response.status === 200) {
      playlist = json
    } else {
      goto(404, `playlists/${id}`)
    }
  })

  // Close the dialog and reset input values
  function handleClose () {
    showRemoveDialog = false
    showCreateRadioDialog = false
    error = null
  }

  // Open remove dialog and store song data for confirmation
  function openRemoveDialog (event, songId, songIndex) {
    event.stopPropagation()
    showRemoveDialog = true
    _id = songId
    index = songIndex
  }

  // Remove a song
  async function removeSong () {
    const response = await del(`playlists/${id}/songs/${_id}`)

    const json = await response.json()

    if (response.status === 200) {
      playlist.songs = [...playlist.songs.slice(0, index), ...playlist.songs.slice(index + 1)]
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

  async function drop (event, newPosition) {
    event.preventDefault()
    event.dataTransfer.dropEffect = 'move'
    const currentPosition = parseInt(event.dataTransfer.getData('text/plain'))
    const newSonglist = playlist.songs

    if (currentPosition < newPosition) {
      newSonglist.splice(newPosition + 1, 0, newSonglist[currentPosition])
      newSonglist.splice(currentPosition, 1)
    } else {
      newSonglist.splice(newPosition, 0, newSonglist[currentPosition])
      newSonglist.splice(currentPosition + 1, 1)
    }

    const response = await put(`playlists/${id}/songs`, {
      currentPosition: currentPosition + 1,
      newPosition: newPosition + 1
    })

    if (response.status === 200) {
      playlist.songs = newSonglist
    }

    hovering = null
  }

  function dragstart (event, i) {
    event.dataTransfer.effectAllow = 'move'
    event.dataTransfer.dropEffect = 'move'
    const start = i
    event.dataTransfer.setData('text/plain', start)
  }

  async function createRadio () {
    const response = await post('radios', {
      name,
      description,
      genre,
      playlist_id: id
    })

    const json = await response.json()
    if (response.status === 200) {
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
  .is-active {
    background-color: #3273dc;
    color: #fff;
  }

  .createradioform {
    display: flex;
    flex-direction: column;
    max-width: 300px;
  }
</style>

{#if playlist}
  <h1>{playlist.name}</h1>

  <Button on:click={() => { showCreateRadioDialog = true }}>Create Radio</Button>

  {#if playlist.songs}
    {#each playlist.songs as song, songIndex (songIndex)}
      <div
        class="listitemwrapper"
        animate:flip
        draggable={true}
        on:dragstart={event => dragstart(event, songIndex)}
        on:drop={event => drop(event, songIndex)}
        on:dragover={event => event.preventDefault()}
        on:dragenter={() => { hovering = songIndex }}
        class:is-active={hovering === songIndex}
      >
        <ListItem>
          <ListItemText>{song.title}</ListItemText>
          <IconButton icon={times} on:click={e => openRemoveDialog(e, song.id, songIndex)} />
        </ListItem>
      </div>
    {/each}
  {/if}
{/if}

{#if showRemoveDialog}
  <Dialog onClose={handleClose} onConfirm={removeSong} title="Remove song from playlist">
    <p>Are you sure you want to remove this song?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}

{#if showCreateRadioDialog}
  <Dialog onClose={handleClose} onConfirm={createRadio} title="Create a radio">
    <div class="createradioform">
      <Input placeholder="Name" bind:value={name} />
      <textarea placeholder="Description" bind:value={description}></textarea>
      <Input placeholder="Genre" bind:value={genre} />
      {#if error}
        <p class="error">Error: {error}</p>
      {/if}
    </div>
  </Dialog>
{/if}
