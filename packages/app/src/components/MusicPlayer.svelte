<script>
    import Icon from 'svelte-awesome/components/Icon.svelte'
    import IconButton from './IconButton.svelte'

    import { play as playIcon, pause as pauseIcon, volumeUp as volumeIcon } from 'svelte-awesome/icons'

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
     border-top: 1px solid  rgb(255, 100, 3);
     box-shadow: inset 0px 1px 16px 7px rgba(0,0,0,0.31);
     display: flex;
     justify-content: center;
     align-items: center;
     background-color: rgb(54, 54, 54);
     line-height: .5;
 }

 input[type="range"]:disabled {
   color: rgba(0, 0, 0, 0.54);
 }

 .time {
   color: rgb(207, 207, 207);
   text-align: center;
   font-weight: 100;
   font-size: 1.5rem;
   padding: 1rem;
   line-height: 5rem;
 }

 :global(.playericon) {
   color: rgb(253, 253, 253) !important;
 }
 :global(.playericon:hover) {
   color: rgb(255, 100, 3) !important;
 }

 .placeholder {
   height: 60px;
   border: 1px solid rgb(153, 153, 153);
   box-shadow: 10px 10px 10px 0px rgba(0,0,0,0.4);
   }

  :global(.volumeicon) {
    margin-right: .5em;
    margin-left: 1em;
  }

</style>

{#if $session.authenticated}
<div class="playingBar">
    <div class="playingBarMetaData">
        <!--

        -->
    </div>

<img class="placeholder" src="radioPlaceholder.png" alt="Radio Icon">

    <div class="playingBarControl">
      {#if togglePlay}
        <IconButton class="playericon" icon={pauseIcon} on:click={stop}></IconButton>
      {:else}
        <IconButton class="playericon" disabled={!$src} icon={playIcon} on:click={play}></IconButton>
      {/if}
        <Icon class="playericon volumeicon" data={volumeIcon} ></Icon>
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
