<script context="module">
  export function preload(page, session) {
    const { authenticated } = session;

    if (!authenticated) {
      return this.redirect(302, "login");
    }
  }
</script>

<script>
  import Input from "../components/Input.svelte";
  import Button from "../components/Button.svelte";

  import { post } from "api";

  let error;
  let message = "";

  async function uploadSong(event) {
    message = "";
    error = "";
    const {
      title,
      thumbnail,
      genre,
      rating,
      path,
      artist,
      user
    } = event.target;

    var data = new FormData();
    data.append("title", title.value);
    data.append("path", path.files[0]);
    data.append("thumbnail", thumbnail.files[0] || "");
    data.append("genre", genre.value);
    data.append("rating", rating.value);
    data.append("artist", artist.value);

    const response = await fetch("http://localhost:8080/songUpload", {
      method: "POST",
      mode: "cors",
      credentials: "include",
      headers: {
        Accept: "application/json"
      },
      body: data
    });

    if (response.status === 200) {
      message = "Song was uploaded!";
    } else {
      const json = await response.json();
      if (json.error) {
        error = json.error;
      } else {
        error = Object.keys(json)
          .map(key => {
            return json[key].join("");
          })
          .join(" ");
        console.log(error);
      }
    }
  }
</script>

<h1>Song Upload!</h1>

<form on:submit|preventDefault={uploadSong}>
  <Input type="text" name="title" placeholder="title" />
  <Input type="file" name="thumbnail" placeholder="Thumbnail-Path" />
  <Input type="text" name="genre" placeholder="genre" />
  <Input type="text" name="rating" placeholder="rating" />
  <Input type="file" name="path" placeholder="songPath" />
  <Input type="text" name="artist" placeholder="artist" />
  <Button type="submit">Upload</Button>
  <p>{message}</p>
  {#if error}
    <p class="error">Error: {error}</p>
  {/if}
</form>
