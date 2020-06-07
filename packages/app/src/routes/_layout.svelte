<script>
	import Nav from '../components/Nav.svelte'
	import MusicPlayer from '../components/MusicPlayer.svelte'
import Toast from '../components/Toast.svelte'

	export let segment
</script>

<style>
	main {
		position: relative;
		max-width: 56em;
		padding: 2em;
		margin: 130px auto 100px;
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
<Toast/>
