<script>
  import { createEventDispatcher } from 'svelte'

  export let ref = null
  export let type = 'text'
  export let name = null
  export let placeholder = null
  export let value = ''

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
  {type}
  {name}
  {placeholder}
  {value}
  on:input={handleInput}
  on:keydown={handleKeydown}
>
