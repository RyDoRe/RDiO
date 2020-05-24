<script>
  export let type = 'text'
  export let name = null
  export let placeholder = null
</script>

<input
  {type}
  {name}
  {placeholder}
>

<style>

input {
    font-size: 18px;
    font-weight: 100;
    color: white;
    border-radius: 5px;
    padding: 0.5em 1em;
    margin: 0 1em;
    background: transparent;
    border: 2px solid rgb(255, 255, 255);
}

</style>