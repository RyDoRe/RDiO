<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>
<script> 
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
song.artist.name.toLowerCase().includes(searchTerm.toLowerCase()) || song.genre.toLowerCase().includes(searchTerm.toLowerCase())))

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
   if (playlists && playlists.length !== 0) {
     playlistId = json[0].id
   }
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

<style>
  table{
    text-overflow: ellipsis;
  }

  td, th{
    padding: 0px 10px 0px 0px;
    text-align: center;
    
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    max-width: 20%;
  }

  .titeltH {
    text-align: left;
  }

  



</style>

<h1>Songs</h1>
  {#if filteredSongs}
    <h2>{songs.length} Songs</h2>
    <Input placeholder="Search..." bind:value={searchTerm} />
    <table style="width:100%">  
        <tr>
          <th>Titel</th>
          <th>Rating</th>
          <th>Genre</th>
          <th>Artist</th>
          <th></th>
        </tr>
        {#each filteredSongs as song, songIndex (song.id)}
          <!-- <div
            class="listitemwrapper"

          > -->
            <!-- <ListItem>
              <ListItemText>{song.title}</ListItemText>

              <span style="color: white;">{song.artist.name}</span>
              <IconButton icon={plus} on:click={e => openAddToPlaylistDialog(e, song.id, songIndex)} />
              <IconButton icon={times} on:click={e => openRemoveDialog(e, song.id, songIndex)} />
            </ListItem> -->
            <tr>
              <td class="titeltH">{song.title}</td>
              <td style="text-align: center">{song.rating}/5</td>
              <td>{song.genre}</td>
              <td>{song.artist.name}</td>
              <td>
                <IconButton icon={plus} on:click={e => openAddToPlaylistDialog(e, song.id, songIndex)} />
                <IconButton icon={times} on:click={e => openRemoveDialog(e, song.id, songIndex)} />
              </td>
            </tr>
          <!-- </div> -->
        {/each}
     </table>  
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
    {#if playlists && playlists.length !== 0}
      <p>Select a playlist to add the song to</p>
      <select name="playListSelect" id="" bind:value={playlistId}>
        
          {#each playlists as playlist (playlist.id)}
            <option value={playlist.id}>{playlist.name}</option>
          {/each}
      
      </select>
      {#if error}
        <p class="error">Error: {error}</p>
      {/if}
    {:else} 
      <p>First you have to create a playlist!</p>
    {/if}
  </Dialog>
{/if}
