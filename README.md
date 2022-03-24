# Media Hut

When I went looking, in early 2022, the options for a truly single-file, no-database drop-in PHP media gallery were shockingly small.

Well, there _was_ one really great one, but it kind of cheated by pulling in the guts from a CDN. If the CDN goes down, or the developer decides to take their ball and go home, there goes your gallery.

Everything else wanted a database setup, or was just way, way outside the scope of what I wanted: just cleanly serve images, video, audio, pdf and text assets using the filesystem for organization.

Don't overthink the capabilities of this: you won't find tags, comments, or much of anything outside of directories and filenames. I may add optional features, including markdown directory docs, and thumbnail caching, but that's the primary use case. Just show media.

## Problems (or "To Do")

- Currently does not generate thumbnails. So it's literally downloading all the media data (for gifs and images, at least) at once. Hope you're not on a mobile plan. _This is at the top of my to-do list._
- Clicking on a file just opens it up directly in the browser, instead of something nicer. Not exactly in a hurry to change that, but it's on my mind.

- Persistent config data would be nice, but since it's currently just a couple at the moment, you'll survive.

- This could probably all be implemented better -- the build process, most especially. `build.sh` is a real shit-show, but it works.

- Themes?

## Give It To Me

I keep a fresh copy in the `/build` directory of the `master` build. Literally just copy/paste, edit the config block pointing to a media path (it defaults to `/media`), and off you go.

## Demo

I built this for my secret cave of Max Headroom and V (1983) pop culture worship. Check it out over here: https://media.network47.org.

<img src="https://i.imgur.com/bUtx4BG.png" width="512"/>