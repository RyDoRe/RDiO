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
