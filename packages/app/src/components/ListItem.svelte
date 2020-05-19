<script>
  const className = null
  export { className as class }
</script>

<style>
  .listitem {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: start;
    padding: 8px 16px;
    cursor: pointer;
    transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
    box-sizing: border-box;
  }

  .listitem:hover {
    background-color: rgba(0, 0, 0, 0.04);
  }
</style>

<div
  class="listitem"
  on:click
>
  <slot></slot>
</div>
