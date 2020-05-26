<script>
  import Button from './Button.svelte'

  export let onClose = null
  export let onConfirm = null
  export let title = null

  function handleCancel (event) {
    if (onClose) {
      onClose(event)
    }
  }

  function handleConfirm (event) {
    if (onConfirm) {
      onConfirm(event)
    }
  }
</script>

<style>
  .backdrop {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2;
  }

  .dialog {
    margin: 32px;
    background: rgb(54, 54, 54);
    box-sizing: border-box;
    max-width: 600px;
    box-shadow: 0px 11px 15px -7px rgba(0,0,0,0.2), 0px 24px 38px 3px rgba(0,0,0,0.14), 0px 9px 46px 8px rgba(0,0,0,0.12);
    border-radius: 4px;
  }

  .dialogtitle {
    margin: 0;
    padding: 16px 24px;
    box-sizing: border-box;
  }

  .dialogtitle h2 {
    font-weight: bold;
    font-size: 1.25rem;
    margin: 0;
  }

  .content {
    padding: 8px 24px;
    box-sizing: border-box;
  }

  .content > :global(p) {
    margin: 0;
    margin-bottom: 12px;
    color: white;
  }

  .actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 8px;
  }

  .actions > :global(:not(:first-child)) {
    margin-left: 8px;
  }

</style>

<div class="backdrop">
  <div class="dialog">
    {#if title}
      <div class="dialogtitle">
        <h2>{title}</h2>
      </div>
    {/if}
    <div class="content">
      <slot></slot>
    </div>
    <div class="actions">
      <Button on:click={handleCancel}>Cancel</Button>
      <Button on:click={handleConfirm}>Ok</Button>
    </div>
  </div>
</div>
