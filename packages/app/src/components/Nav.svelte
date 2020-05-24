<script>
  import { stores } from '@sapper/app'

  const { session } = stores()

  export let segment
</script>


<figure>
	<img alt='Avatar' src='avatar.png' class='avatar'>
</figure>


<style>
	figure {
		margin: 0 0 1em 0;
	}

	.avatar {
		vertical-align: middle;
		width: 50px;
		height: 50px;
		float: right;
		margin: 1em;
	}

	.avatar:hover {
		border-bottom: 2px solid rgb(255, 100, 3);
	}


	nav {
		font-weight: 200;
		padding: 1em;
		display: flex;
		position: relative;
		justify-content: space-between;
		float: right;
	}

	a:hover {
		color: rgb(255, 89, 0);
	}


	ul {
		text-align: right;
		margin: 0;
		padding: 0;
		overflow: hidden;
	}

	ul::after {
		content: '';
		display: block;
		clear: both;
	}


	li {
		display: inline;
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
		background-color: rgb(230, 230, 230);
		display: block;
		bottom: -1px;
	}

	a {
		text-decoration: none;
		padding: 1em 1em;
		display: block;
		float:right;
	}
</style>

<nav>
	<ul>
		<li <a aria-current='{segment === undefined ? "page" : undefined}' href='.'>home</a></li>
	</ul>

	<ul>
		<li style="float: right"><a class="active" href='.'>songs</a></li>
	</ul>

	<ul>
		<li style="float: right"><a class="active" href='.'>settings</a></li>
	</ul>

	<ul>
		<li style="float: right"><a class="active" href='.'>radios</a></li>
	</ul>

	<ul>
		<li style="float: right"><a class="active" href='.'>favorites</a></li>
	</ul>

	<ul>
		<li style="float: right"><a class="active" href='.'>playlists</a></li>
	</ul>


  {#if $session.authenticated}
    <ul>
      {#if $session.role === 'admin'}
        <li><a aria-current='{segment === 'users' ? "page" : undefined}' href='users'>users</a></li>
      {/if}
      <li><a aria-current="{segment === 'profile' ? 'page' : undefined }" href="profile">{$session.username}</a></li>
      <li><a href="logout">logout</a></li>
    </ul>
  {/if}
</nav> */
