<script>
    import IconButton from './IconButton.svelte'

    import { play as playIcon, pause as pauseIcon } from 'svelte-awesome/icons'

    import { stores } from '@sapper/app'
    import { src } from '../store'

    const { session } = stores()

    let audio
    let currentTime
    let volume = 20
    let togglePlay = 0

    $: {
      if (audio) {
        audio.volume = volume / 100
      }
    }

    src.subscribe(value => {
      if (audio) {
        audio.pause()
        audio.load()
        play()
      }
    })

    // function to start the player
    function play () {
      audio.play()
      setInterval(() => {
        currentTime = audio.currentTime
      }, 1000)
      togglePlay = 1
    }

    // function to stop the player
    function stop () {
      audio.pause()
      togglePlay = 0
    }

    // function to format the given timeformat
    function formatTime (_seconds = 0) {
      const time = Math.round(_seconds)
      const minutes = Math.floor(time / 60)
      const seconds = time - (minutes * 60)

      let extraZero

      if (seconds < 10) {
        extraZero = '0'
      } else {
        extraZero = ''
      }

      return minutes + ':' + extraZero + seconds
    }
</script>

<style>
 .playingBar {
     width: 100%;
     position: fixed;
     bottom: 0;
     left: 0;
     border-top: 1px solid black;
     display: flex;
     justify-content: center;
     align-items: center;
 }

 input[type="range"]:disabled {
   color: rgba(0, 0, 0, 0.54);
 }

 .time {
   color: white;
 }
</style>

{#if $session.authenticated}
<div class="playingBar">
    <div class="playingBarMetaData">
        <!--

        -->
    </div>

    <div class="playingBarControl">
      {#if togglePlay}
        <IconButton icon={pauseIcon} on:click={stop}></IconButton>
      {:else}
        <IconButton disabled={!$src} icon={playIcon} on:click={play}></IconButton>
      {/if}
    </div>

    <div class="playingBarVolume">
      <input type="range" min="0" max="100" bind:value={volume}>
      <audio preload="none" bind:this={audio}>
        <source src={$src} />
      </audio>
      <span class="time">{formatTime(currentTime)}</span>
    </div>


</div>
{/if}
