<?php
/* This code is built by a script so it's ugly as hell. But easier to maintain. */
/* ------------------- CONFIG --------------------------- */
define('MEDIA_ROOT', "./media");
define('MEDIA_TYPES', "jpg,jpeg,gif,png,bmp,webp,mp4,mkv,mov,wav,ogg,mp3,pdf,txt");
/* ------------------------------------------------------ */

$has_dirs = false;

define('HEADER_HTML', <<<HEADER_HTML
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
    <title>$path</title>
</head>
<style>
    ##CSS##
</style>

<body>
HEADER_HTML);

define('FOOTER_HTML', <<<FOOTER_HTML
<footer>
    <center>This gallery software is very much a WIP. It's exceedingly simplistic.</center>
</footer>
</body>

</html>
FOOTER_HTML);
define('CSS', <<<CSS
body{padding:1em}audio,img,video{height:100%;width:100%}hr.section{margin:3rem 1rem}.media-container{display:grid;gap:2em;grid-auto-rows:minmax(100px,auto);grid-template-columns:repeat(4,1fr)}.dir-entry{background-color:#f003;background:#020024;background:linear-gradient(52deg,rgba(0,0,0,.15),hsla(0,0%,100%,.25));border-radius:10px;box-shadow:0 0 20px #000;font-weight:700;height:100%;justify-content:center;text-align:center;text-decoration:underline}.dir-entry,.entry{display:flex;flex-direction:column;overflow:hidden}.entry{background-color:#0003;box-shadow:0 3px 20px #000;justify-content:top;padding:.25em;position:relative;transition:box-shadow .25s,top .25s}.entry:hover{box-shadow:0 0 3px #000;top:3px}.filename{color:#fff;font-size:.8em;line-height:1;overflow:hidden;padding:.25em;text-align:center;text-transform:capitalize}
CSS);
?>
<?php
/* -- PAGE TEMPLATES --------------------------- */
function pageHeader($path)
{
    return str_replace('##CSS##', CSS, HEADER_HTML);
}

function pageFooter()
{
    return FOOTER_HTML;
}

function pageMediaStart()
{
    return "<div class='media-container'>";
}

function pageMediaEnd()
{
    return "</div>";
}

function getRenderer($path)
{
    $ext = pathinfo($path, PATHINFO_EXTENSION);

    switch ($ext) {
        case 'mp4':
        case 'mov':
            return "<video controls src='$path'/>";
        case 'wav':
        case 'ogg':
        case 'mp3':
            return "<audio controls src='$path'/>";
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
            return "<img src='$path'/>";
        default:
            return "";
    }
}

function renderMedia($path)
{
    $base = basename($path);
    $path = htmlspecialchars($path, ENT_QUOTES);
    $rendered = getRenderer($path);

    if (strpos($base, ' ') === false) {
        if (strlen($base) > 32) {
            $base = substr($base, 0, 32) . "...";
        }
    }

    return <<<MEDIA
    <a href='$path'><div class="entry">
        <div class="filename">$base</div>
        <div class='entry-object'>
            $rendered
        </div>
    </div></a>
MEDIA;
}

function renderDirectories($path)
{
    global $has_dirs;

    $globpath = MEDIA_ROOT . $path . DIRECTORY_SEPARATOR . "*";
    $globs = glob($globpath, GLOB_ONLYDIR);

    if ($globs) {
        $has_dirs = true;
        echo pageMediaStart();

        foreach ($globs as $dir) {
            $d = basename($dir);
            echo "<a href='index.php?path=$path/$d'><div class='dir-entry'>$d</div></a>";
        }

        echo pageMediaEnd();
    }
}

function renderAssets($path)
{
    global $has_dirs;

    $globpath = MEDIA_ROOT . $path . DIRECTORY_SEPARATOR . "*.{" . MEDIA_TYPES . "}";
    $globs = glob($globpath, GLOB_BRACE | GLOB_MARK);
    sort($globs, SORT_NATURAL | SORT_FLAG_CASE);

    if ($globs) {

        if ($has_dirs) {
            echo "<hr class='section'/>";
        }

        echo pageMediaStart();

        foreach ($globs as $file) {
            echo renderMedia($file);
        }
    }

    echo pageMediaEnd();
}

/* ---------------------------- */
function main()
{
    $userPath = array_key_exists('path', $_GET) ? $_GET['path'] : '';
    if ($userPath === '/') {
        header('Location: /');
        $userPath = '';
        // die;
    }

    if (!empty($userPath)) {
        // reject path traversal
        $a = pathinfo(__FILE__, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . MEDIA_ROOT . DIRECTORY_SEPARATOR . $userPath;
        $b = pathinfo(__FILE__, PATHINFO_DIRNAME);

        $foo = realpath($a);
        $bar = realpath($b);

        $isok = strpos($foo, $bar) !== false;

        if (!$isok) {
            header('Location: /');
            die();
        }
    }

    echo pageHeader($userPath);

    echo "<h1><a href='index.php?path=" . dirname($userPath, 1) . "'>" . $userPath . "</a></h1>";

    renderDirectories($userPath);
    renderAssets($userPath);

    echo pageFooter();
}
main();
