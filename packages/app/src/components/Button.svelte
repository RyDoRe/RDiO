<script>
  export let type = null
</script>

<style>
  button {
    font-size: 18px;
    font-weight: bolder;
    border-radius: 5px;
    padding: 0.5em 1em;
    background: transparent;
    color: rgb(255, 255, 255);
    border: 2px solid rgb(255, 100, 3);
  }

  button:hover {
    border: 2px solid rgb(228, 228, 228);
    color: rgb(255, 100, 3);
  }
</style>

<button {type} on:click>
  <slot>
  </slot>
</button>
