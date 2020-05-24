<script>
	import Nav from '../components/Nav.svelte'

	export let segment
</script>

<style>
	main {
		position: relative;
		max-width: 56em;
		background-color: rgb(54, 54, 54);
		padding: 2em;
		margin: 0 auto;
		box-sizing: border-box;
	}

	:global(body) {
		background-color: rgb(54, 54, 54);
	}

	:global(h1) {
		color: rgb(255, 100, 3);
	}

	:global(li) {
		color: rgb(255, 255, 255);
		float: right;
	}

	:global(button) {
		font-size: 18px;
		font-weight: bolder;
		border-radius: 5px;
		padding: 0.5em 1em;
		margin: 0 1em;
		background: transparent;
		color: rgb(255, 255, 255);
		border: 2px solid rgb(255, 100, 3);
  	}

  	:global(button.hover){
		border: 2px solid rgb(228, 228, 228);
		color: rgb(255, 100, 3);
	}
</style>

<Nav {segment}/>

<main>
	<slot></slot>
</main>