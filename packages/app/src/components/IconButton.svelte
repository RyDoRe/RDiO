<script>
  import Icon from 'svelte-awesome/components/Icon.svelte'

  import { createEventDispatcher } from 'svelte'

  const dispatch = createEventDispatcher()

  export let icon = null
  export let disabled = false
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
    background-color: rgba(0, 0, 0, 0.04);
  }

  .iconbuttondisabled {
    cursor: default;
    color: rgb(197, 197, 197);
  }

  .iconbuttondisabled:hover {
    cursor: default;
    color: rgb(197, 197, 197);
    background-color: transparent;
  }
</style>

<div class="iconbutton" class:iconbuttondisabled={disabled} on:click={() => !disabled && dispatch('click')}>
  <Icon data={icon} />
</div>
