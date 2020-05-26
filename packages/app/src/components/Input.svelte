<script>
  import { createEventDispatcher } from 'svelte'

  export let ref = null
  export let type = 'text'
  export let name = null
  export let placeholder = null
  export let value = ''
  export let id = null

  const dispatch = createEventDispatcher()

  function handleInput (event) {
    value = event.target.value
  }

  function handleKeydown (event) {
    if (event.key === 'Enter') {
      dispatch('enter', event)
    }
  }
</script>

<style>
  input {
    font-size: 18px;
    font-weight: 100;
    color: white;
    border-radius: 5px;
    padding: 0.5em 1em;
    background: transparent;
    border: 2px solid rgb(255, 255, 255);
    margin-bottom: 15px;
  }
</style>

<input
  bind:this={ref}
  {id}
  {type}
  {name}
  {placeholder}
  {value}
  on:input={handleInput}
  on:keydown={handleKeydown}
>
