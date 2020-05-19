<script>
  let className = null
  export { className as class }
</script>

<style>
  .listitemtext {
    flex: 1 1 auto;
  }
</style>

<div class="listitemtext {className}">
  <slot></slot>
</div>
