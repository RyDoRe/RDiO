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
  import IconButton from '../../components/IconButton.svelte'
  import Dialog from '../../components/Dialog.svelte'
  import Icon from 'svelte-awesome/components/Icon.svelte'

  import { src } from '../../store'

  import { play, powerOff, times } from 'svelte-awesome/icons'

  import { get, put, del } from 'api'
  import { onMount } from 'svelte'

  let myRadios
  let radios

  let showDeleteDialog = false

  let id
  let index
  let error

  onMount(async () => {
    const responseMyRadios = await get('radios/my')
    const jsonMyRadios = await responseMyRadios.json()

    if (responseMyRadios.status === 200) {
      myRadios = jsonMyRadios
    }

    const responseRadios = await get('radios')
    const jsonRadios = await responseRadios.json()

    if (responseRadios.status === 200) {
      radios = jsonRadios
    }
  })

  function playRadio (event, radioId) {
    event.stopPropagation()
    src.update(s => `http://localhost:8080/radios/${radioId}/stream`)
  }

  async function activateRadio (event, radioId, radioIndex) {
    event.stopPropagation()
    const response = await put(`radios/${radioId}/activate`)

    const json = await response.json()
    if (response.status === 200) {
      myRadios = myRadios.map((radio, _radioIndex) => {
        if (radioIndex !== _radioIndex) {
          return radio
        }

        return {
          ...radio,
          active: json.active
        }
      })
    }
  }

  // Close the dialog and reset input values
  function handleClose () {
    showDeleteDialog = false
    error = null
  }

  function openDeleteDialog (event, radioId, radioIndex) {
    event.stopPropagation()
    showDeleteDialog = true
    id = radioId
    index = radioIndex
  }

  async function deleteRadio () {
    const response = await del(`radios/${id}`)

    if (response.status === 200) {
      myRadios = [...myRadios.slice(0, index), ...myRadios.slice(index + 1)]
      handleClose()
    }
  }
</script>

<style>
  .iconbutton {
    color: rgba(0, 0, 0, 0.54);
    padding: 12px;
    font-size: 1.5rem;
    text-align: center;
    transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border-radius: 50%;
    box-sizing: border-box;
    display: inline-flex;
  }

  .iconbutton:hover {
    background-color: rgb(197, 197, 197);
  }
  .radio-on {
    color: green;
  }

  .radio-off {
    color: red;
  }
</style>

<h1>Radios</h1>

<h3>My radios</h3>
{#if myRadios}
  {#each myRadios as radio, radioIndex (radio.id)}
    <ListItem>
      <ListItemText>{radio.name}</ListItemText>
        <IconButton disabled={!radio.active} icon={play} on:click={e => playRadio(e, radio.id)} />
        <div class="iconbutton" class:radio-on={radio.active} class:radio-off={!radio.active} on:click={e => activateRadio(e, radio.id, radioIndex)}>
          <Icon  data={powerOff} />
        </div>
        <IconButton icon={times} on:click={e => openDeleteDialog(e, radio.id, radioIndex)} />
    </ListItem>
  {/each}
{/if}

<hr>

<h3>Other radios</h3>

{#if radios}
  {#each radios as radio, radioIndex (radio.id)}
    <ListItem>
      <ListItemText>{radio.name}</ListItemText>
        <IconButton icon={play} on:click={e => playRadio(e, radio.id)} />
    </ListItem>
  {/each}
{/if}

{#if showDeleteDialog}
  <Dialog onClose={handleClose} onConfirm={deleteRadio} title="Delete radio">
    <p>Are you sure you want to delete this radio?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}
