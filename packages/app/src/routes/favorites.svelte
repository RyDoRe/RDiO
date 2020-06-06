<script context="module">
  export function preload (page, session) {
    const { authenticated } = session

    if (!authenticated) {
      return this.redirect(302, 'login')
    }
  }
</script>

<script>
  import ListItem from '../components/ListItem.svelte'
  import ListItemText from '../components/ListItemText.svelte'
  import IconButton from '../components/IconButton.svelte'

  import { src } from '../store'

  import { play, heart } from 'svelte-awesome/icons'

  import { get, post, baseURL } from 'api'
  import { onMount } from 'svelte'

  let favorites

  onMount(async () => {
    const response = await get('radios/favorites')
    const json = await response.json()

    if (response.status === 200) {
      favorites = json
    }
  })

  function playRadio (event, radioId) {
    event.stopPropagation()
    src.update(s => `${baseURL}/radios/${radioId}/stream`)
  }

  async function toggleFavorite (event, radioId, radioIndex) {
    const response = await post('radios/favorites', {
      radio_id: radioId
    })

    const json = await response.json()
    if (response.status === 200) {
      window.pushToast(json.message)
      favorites = [...favorites.slice(0, radioIndex), ...favorites.slice(radioIndex + 1)]
    }
  }
</script>

<h1>Favorites</h1>

{#if favorites}
  {#each favorites as radio, radioIndex}
    <ListItem>
      <ListItemText>{radio.name}</ListItemText>
        <IconButton disabled={!radio.active} icon={play} on:click={e => playRadio(e, radio.id)} />
        <IconButton icon={heart} on:click={e => toggleFavorite(e, radio.id, radioIndex)} />
    </ListItem>
  {/each}
{/if}
