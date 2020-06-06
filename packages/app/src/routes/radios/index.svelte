<script context="module">
  export function preload(page, session) {
    const { authenticated } = session;

    if (!authenticated) {
      return this.redirect(302, "login");
    }
  }
</script>

<script>
  import { stores } from "@sapper/app";

  import IconButton from "../../components/IconButton.svelte";
  import Dialog from "../../components/Dialog.svelte";
  import Icon from "svelte-awesome/components/Icon.svelte";
  import Input from "../../components/Input.svelte";

  import { src } from "../../store";

  import { play, powerOff, times } from "svelte-awesome/icons";

  import { get, put, del, baseURL } from "api";
  import { onMount } from "svelte";

  let myRadios;
  let radios;
  let myFilteredRadios;
  let filteredRadios;

  let showDeleteDialog = false;

  let id;
  let index;
  let error;

  const { session } = stores();

  // Search
  let searchTerm = "";
  // Filter other user radios based on the searchTerm
  $: radios &&
    (filteredRadios = radios.filter(
      radio =>
        radio.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        radio.user.name.toLowerCase().includes(searchTerm.toLowerCase())
    ));

  // Filter own  radios based on the searchTerm
  $: myRadios &&
    (myFilteredRadios = myRadios.filter(
      myRadio =>
        myRadio.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        myRadio.user.name.toLowerCase().includes(searchTerm.toLowerCase())
    ));

  onMount(async () => {
    const responseMyRadios = await get("radios/my");
    const jsonMyRadios = await responseMyRadios.json();

    if (responseMyRadios.status === 200) {
      myRadios = jsonMyRadios;
    }

    const responseRadios = await get("radios");
    const jsonRadios = await responseRadios.json();

    if (responseRadios.status === 200) {
      radios = jsonRadios;
    }
  });

  //function call to start the radiostream
  function playRadio(event, radioId) {
    event.stopPropagation();
    src.update(s => `${baseURL}/radios/${radioId}/stream`);
  }

  //function call to activate own radio
  async function activateRadio(event, radioId, radioIndex) {
    event.stopPropagation();
    const response = await put(`radios/${radioId}/activate`);

    const json = await response.json();
    if (response.status === 200) {
      myRadios = myRadios.map((radio, _radioIndex) => {
        if (radioIndex !== _radioIndex) {
          return radio;
        }

        return {
          ...radio,
          active: json.active
        };
      });
    }
  }

  // Close the dialog and reset input values
  function handleClose() {
    showDeleteDialog = false;
    error = null;
  }

  //Open the delete dialog
  function openDeleteDialog(event, radioId, radioIndex) {
    event.stopPropagation();
    showDeleteDialog = true;
    id = radioId;
    index = radioIndex;
  }

  //function call to delete the radio
  async function deleteRadio() {
    const response = await del(`radios/${id}`);

    if (response.status === 200) {
      myRadios = [...myRadios.slice(0, index), ...myRadios.slice(index + 1)];
      handleClose();
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

<Input placeholder="Search..." bind:value={searchTerm} />
<table style="width:100%">
  {#if $session.role !== 'user'}
    <tr>
      <h3>My radios</h3>
    </tr>
    <tr>
      <th>Radioname</th>
      <th>Creator</th>
      <th />
    </tr>

    {#if myFilteredRadios}
      {#each myFilteredRadios as radio, radioIndex (radio.id)}
        <tr>
          <td class="titeltH">{radio.name}</td>
          <td style="text-align: center">{radio.user.name}</td>

          <td>
            <IconButton
              disabled={!radio.active}
              icon={play}
              on:click={e => playRadio(e, radio.id)} />
            <div
              class="iconbutton"
              class:radio-on={radio.active}
              class:radio-off={!radio.active}
              on:click={e => activateRadio(e, radio.id, radioIndex)}>
              <Icon data={powerOff} />
            </div>
            <IconButton
              icon={times}
              on:click={e => openDeleteDialog(e, radio.id, radioIndex)} />
          </td>
        </tr>
      {/each}
    {/if}
    <tr>

      <td colspan="3">
        <hr />
      </td>

    </tr>
    <tr>
      <h3>Other radios</h3>
    </tr>
  {:else}
    <tr>
      <h3>Radios</h3>
    </tr>
    <tr>
      <th>Radioname</th>
      <th>Creator</th>
      <th />
    </tr>
  {/if}

  {#if filteredRadios}
    <tr>
      <th />
    </tr>
    {#each filteredRadios as radio, radioIndex (radio.id)}
      <tr>
        <td class="titeltH">{radio.name}</td>
        <td style="text-align: center">{radio.user.name}</td>

        <td>
          <IconButton icon={play} on:click={e => playRadio(e, radio.id)} />
        </td>
      </tr>
    {/each}
  {/if}
</table>
{#if showDeleteDialog}
  <Dialog onClose={handleClose} onConfirm={deleteRadio} title="Delete radio">
    <p>Are you sure you want to delete this radio?</p>
    {#if error}
      <p class="error">Error: {error}</p>
    {/if}
  </Dialog>
{/if}
