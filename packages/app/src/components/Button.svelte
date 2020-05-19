<script>
  export let type = null
</script>

<button {type} on:click>
  <slot>
  </slot>
</button>
