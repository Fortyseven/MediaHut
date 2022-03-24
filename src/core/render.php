?>
<?php
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
