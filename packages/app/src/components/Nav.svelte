<script>
  import { stores } from '@sapper/app'

  const { session } = stores()

  export let segment
</script>

<style>
	nav {
		border-bottom: 1px solid rgba(255,62,0,0.1);
		font-weight: 300;
		padding: 0 1em;
    display: flex;
    justify-content: space-between;
	}

	ul {
		margin: 0;
		padding: 0;
	}

	/* clearfix */
	ul::after {
		content: '';
		display: block;
		clear: both;
	}

	li {
		display: block;
		float: left;
	}

	[aria-current] {
		position: relative;
		display: inline-block;
	}

	[aria-current]::after {
		position: absolute;
		content: '';
		width: calc(100% - 1em);
		height: 2px;
		background-color: rgb(255,62,0);
		display: block;
		bottom: -1px;
	}

	a {
		text-decoration: none;
		padding: 1em 0.5em;
		display: block;
	}
</style>

<nav>
	<ul>
		<li><a aria-current='{segment === undefined ? "page" : undefined}' href='.'>home</a></li>
	</ul>
  {#if $session.authenticated}
    <ul>
      <li><a aria-current='{segment === 'playlists' ? 'page' : undefined }' href="playlists">playlists</a></li>
      {#if $session.role === 'admin'}
        <li><a aria-current='{segment === 'users' ? "page" : undefined}' href='users'>users</a></li>
      {/if}
      <li><a aria-current="{segment === 'profile' ? 'page' : undefined }" href="profile">{$session.username}</a></li>
      <li><a href="logout">logout</a></li>
    </ul>
  {/if}
</nav>
