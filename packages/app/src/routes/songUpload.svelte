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

  import { baseURL } from 'api'

  let error
  let message = ''
  let rating = 0

  async function uploadSong (event) {
    message = ''
    error = ''
  
    const {
      title,
      thumbnail,
      genre,
      rating,
      path,
      artist
    } = event.target

    var data = new FormData()
    data.append('title', title.value)
    data.append('path', path.files[0])
    data.append('thumbnail', thumbnail.files[0] || '')
    data.append('genre', genre.value)
    data.append('rating', rating.value)
    data.append('artist', artist.value)

    const response = await fetch(`${baseURL}/songUpload`, {
      method: 'POST',
      mode: 'cors',
      credentials: 'include',
      body: data
    })

    if (response.status === 200) {
      message = 'Song was uploaded!'
    } else {
      const json = await response.json()
      if (json.error) {
        error = json.error
      } else {
        error = Object.keys(json)
          .map(key => {
            return json[key].join('')
          })
          .join(' ')
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

  .message {
    color: white;
  }
</style>

<h1>Song Upload!</h1>

<form on:submit|preventDefault={uploadSong}>
  <label for="title">Songtitle:</label>
  <Input type="text" id="title" name="title" placeholder="title" />
  <label for="thumbnail">Thumbnailfile:</label>
  <Input type="file" id="thumbnail" name="thumbnail" placeholder="Thumbnail-Path" />
  <label for="genre">Genre:</label>
  <Input type="text" id="genre" name="genre" placeholder="genre" />
  <label for="rating">Rating: {rating}</label>
  <input type="range" id="rating" min="0" max="5" step="1" name="rating" bind:value={rating} />
  <label for="path">Songfile:</label>
  <Input type="file" id="path" name="path" placeholder="songPath" />
  <label for="artist">Artist:</label>
  <Input type="text" id="artist" name="artist" placeholder="artist" />
  <Button type="submit">Upload</Button>
  <p class="message">{message}</p>
  {#if error}
    <p class="error">Error: {error}</p>
  {/if}
</form>

  <datalist id="ratingRange">
  <option value="0" label="0"></option>
  <option value="1" label="1"></option>
  <option value="2" label="2"></option>
  <option value="3" label="3"></option>
  <option value="4" label="4"></option>
  <option value="5" label="5"></option>
  </datalist>
