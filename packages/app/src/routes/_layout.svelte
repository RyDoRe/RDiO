<script>
	import Nav from '../components/Nav.svelte'
	import MusicPlayer from '../components/MusicPlayer.svelte'

	export let segment
</script>

<style>
	main {
		position: relative;
		max-width: 56em;
		padding: 2em;
		margin: 0 auto;
		box-sizing: border-box;
	}
</style>


<!--
	general layout of the website
-->
<Nav {segment}/>

<main>
	<slot></slot>
</main>

<MusicPlayer />
