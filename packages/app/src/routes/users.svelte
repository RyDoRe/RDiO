<script context="module">
  export function preload (page, session) {
    const { authenticated, role } = session

    if (!authenticated || role !== 'admin') {
      this.redirect(302, '')
    }
  }
</script>

<h1>Users</h1>
