<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>

<script>
  import ListItem from '../../components/ListItem.svelte'
  import ListItemText from '../../components/ListItemText.svelte'
  import Dialog from '../../components/Dialog.svelte'
  import IconButton from '../../components/IconButton.svelte'
  import Input from '../../components/Input.svelte'

  import { times } from 'svelte-awesome/icons'
  import { plus } from 'svelte-awesome/icons'

  import { get, del, post } from 'api'
  import { onMount } from 'svelte'

  let songs
  let error
  let _id
  let index
  let playlistId
  let playlists

  // Visibilty of dialogs
  let showRemoveDialog = false
  let showAddToPlaylistDialog = false

  // Search
  let searchTerm = ''
  let filteredSongs
  // Filter playlists based on the searchTerm
  $: songs && (filteredSongs = songs.filter(song => song.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
song.artist.name.toLowerCase().includes(searchTerm.toLowerCase())))

  onMount(async () => {
    const response = await get('songs')

    const json = await response.json()

    if (response.status === 200) {
      songs = json
    }
  })

  // Close the dialog and reset input values
  function handleClose () {
    showRemoveDialog = false
    error = null
  }
  // Close the dialog and reset input values
  function handleCloseAddToPlaylist () {
    showAddToPlaylistDialog = false
    error = null
  }

  // Open remove dialog and store song data for confirmation
  function openRemoveDialog (event, songId, songIndex) {
    event.stopPropagation()
    showRemoveDialog = true
    _id = songId
    index = songIndex
  }

  // Open addtoplaylist dialog and store song data for confirmation
  async function openAddToPlaylistDialog (event, songId, songIndex) {
    event.stopPropagation()
    showAddToPlaylistDialog = true
    _id = songId
    index = songIndex

    const response = await get('playlists')
    const json = await response.json()
    playlists = json
  }

  // Remove a song
  async function removeSong () {
    const response = await del(`songs/${_id}`)

    const json = await response.json()

    if (response.status === 200) {
      songs = [...songs.slice(0, index), ...songs.slice(index + 1)]
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

  async function addToPlaylist () {
    const response = await post(`playlists/${playlistId}/songs/${_id}`)

    const json = await response.json()

    if (response.status === 200) {
      handleCloseAddToPlaylist()
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

<h1>Songs</h1>
  {#if filteredSongs}
    <h2>{songs.length} Songs</h2>
    <Input placeholder="Search..." bind:value={searchTerm} />
    {#each filteredSongs as song, songIndex (song.id)}
      <div
        class="listitemwrapper"

      >
        <ListItem>
          <ListItemText>{song.title}</ListItemText>
          <span>{song.artist.name}</span>
          <IconButton icon={plus} on:click={e => openAddToPlaylistDialog(e, song.id, songIndex)} />
          <IconButton icon={times} on:click={e => openRemoveDialog(e, song.id, songIndex)} />
        </ListItem>
      </div>
    {/each}
  {/if}

  {#if showRemoveDialog}
  <Dialog onClose={handleClose} onConfirm={removeSong} title="Delete song">
    <p>Are you sure you want to delete this song?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}

  {#if showAddToPlaylistDialog}
  <Dialog onClose={handleCloseAddToPlaylist} onConfirm={addToPlaylist} title="add song to playlist">
    <p>Select a playlist to add the song to {playlistId}</p>
    <select name="playListSelect" id="" bind:value={playlistId}>
      {#if playlists}
        {#each playlists as playlist (playlist.id)}
          <option value={playlist.id}>{playlist.name}</option>
        {/each}
      {/if}
    </select>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}