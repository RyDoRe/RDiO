<script>
  import Icon from 'svelte-awesome/components/Icon.svelte'

  export let icon = null
</script>

<style>
  .iconbutton {
    color: rgba(0, 0, 0, 0.54);
    padding: 12px;
    font-size: 1.5rem;
    text-align: center;
    transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
    border-radius: 50%;
    box-sizing: border-box;
    display: inline-flex;
  }

  .iconbutton:hover {
    background-color: rgba(0, 0, 0, 0.04);
  }
</style>

<div class="iconbutton" on:click>
  <Icon data={icon} />
</div>
