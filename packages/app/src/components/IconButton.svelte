<script>
  import Icon from 'svelte-awesome/components/Icon.svelte'

  import { createEventDispatcher } from 'svelte'

  const dispatch = createEventDispatcher()

  export let icon = null
  export let disabled = false

  let className = null
  export { className as class }

  function handleClick (event) {
    event.stopPropagation()
    if (!disabled) dispatch('click', event)
  }
</script>

<style>
  .iconbutton {
    padding: 20px;
    color: rgb(197, 197, 197);
    font-size: 1.5rem;
    text-align: center;
    transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border-radius: 50%;
    box-sizing: border-box;
    display: inline-flex;
    min-width: 40px;
    min-height: 40px;
    justify-content: center;
  }

  .iconbutton:hover {
    background-color: rgb(197, 197, 197);
    color: rgb(255, 100, 3);
  }

  .iconbuttondisabled {
    cursor: default;
    color: rgba(0, 0, 0, 0.54);
  }

  .iconbuttondisabled:hover {
    cursor: default;
    background-color: transparent;
  }
</style>

<div class="iconbutton {className}" class:iconbuttondisabled={disabled} on:click={handleClick}>
  <Icon data={icon} />
</div>
