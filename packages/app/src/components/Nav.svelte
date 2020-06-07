<script>
  import { stores } from '@sapper/app'

  const { session } = stores()

  export let segment
</script>

<style>
	nav {
		border-bottom: 1px solid rgb(255,100,3);
		font-weight: 200;
		padding: 0 1em;
		display: flex;
		justify-content: space-between;
		align-items: center;
		box-shadow: 0px 11px 11px -2px rgba(0,0,0,0.38);
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
    	color: rgb(255, 255, 255);
	}

	[aria-current] {
		position: relative;
		display: inline-block;
	}

	[aria-current]::after {
		position: absolute;
		content: '';
		width: calc(100% - 1em);
		height: 1px;
		background-color: rgb(255, 100, 3);
		display: block;
		bottom: 1px;
	}

	a {
		text-decoration: none;
		padding: 0em .5em;
		display: block;
	}

	a:hover {
		color: rgb(255, 100, 3);
	}

  .logo {
	height: 100px;
	padding: .5rem;
  }

  .logoutbutton {
	color: rgb(216, 213, 29);
	font-weight: 600;
  }


</style>

<nav>
	<ul>
		<li><a class="logolink" ria-current='{segment === undefined ? "page" : undefined}' href='.'><img class="logo" src="RDiO_LGO.png" alt="Logo"></a></li>
	</ul>
  {#if $session.authenticated}
    <ul>
      <li><a aria-current='{segment === 'favorites' ? 'page' : undefined }' href="favorites">favorites</a></li>
      <li><a aria-current='{segment === 'radios' ? 'page' : undefined }' href="radios">radios</a></li>
      {#if $session.role === 'broadcaster' || $session.role === 'admin'}
        <li><a aria-current='{segment === 'playlists' ? 'page' : undefined }' href="playlists">playlists</a></li>
        <li><a aria-current='{segment === 'songUpload' ? "page" : undefined}' href='songUpload'>songUpload</a></li>
        <li><a aria-current='{segment === 'songs' ? "page" : undefined}' href='songs'>songs</a></li>
        {#if $session.role === 'admin'}
          <li><a aria-current='{segment === 'users' ? "page" : undefined}' href='users'>users</a></li>
        {/if}
      {/if}
      <li><a aria-current="{segment === 'profile' ? 'page' : undefined }" href="profile">{$session.username}</a></li>
      <li><a class="logoutbutton" href="logout">logout</a></li>
    </ul>
  {/if}
</nav>
