<?php
# This code is built by a script so it's ugly as hell. But easier to maintain.
# And this is a fucking mess anyway; I'm commiting WIP garbage so by all means,
# delight in the chaos.

# ------------------- CONFIG ---------------------------
# Where the media files are; this needs to be inside the public-facing document root.
define('MEDIA_ROOT', "media");

# This is where we'll keep persistent content, like thumbnails and (later) configs
# Nothing of security-related importance will go here so don't worry if anyone gets
# in there and starts poking around. "Oh boy: all the thumbnails!"
define('DATA_ROOT', ".data");
define('THUMBS_ROOT', DATA_ROOT . DIRECTORY_SEPARATOR. "thumbs"); # leave empty to disable
define('THUMB_WIDTH', 200);

# We'll only show these file types; everything else is ignored.
define('MEDIA_TYPES', "jpg,jpeg,gif,png,bmp,webp,mp4,mkv,mov,wav,ogg,mp3,pdf,txt");

/* ------------------------------------------------------ */
/* -------- Nothing below here is confgurable. ---------- */
/* ------------------------------------------------------ */

$has_dirs = false;

define('HEADER_HTML', <<<HEADER_HTML
