<script>
  let className = null
  export { className as class }
</script>

<style>
  .floatingbutton {
    width: 56px;
    height: 56px;
    padding: 0;
    font-size: 0.875rem;
    box-shadow: 0px 3px 5px -1px rgba(0,0,0,0.2), 0px 6px 10px 0px rgba(0,0,0,0.14), 0px 1px 18px 0px rgba(0,0,0,0.12);
    box-sizing: border-box;
    min-height: 36px;
    background-color: #e0e0e0;
    border-radius: 50%;
    transition: background-color 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms,box-shadow 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms,border 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
  }

  .floatingbutton:hover {
    background-color: #d5d5d5;
  }
</style>

<div class="floatingbutton {className}" on:click>
  <slot></slot>
</div>
