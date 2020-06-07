<script context="module">
  import { del } from 'api'

  export async function preload (page, session) {
    await del('auth/logout')
  }
</script>

<script>
  import { goto, stores } from '@sapper/app'

  const { session } = stores()

  // reset autentication and username
  session.set({ authenticated: false, username: '' })

  goto('/login')
</script>
