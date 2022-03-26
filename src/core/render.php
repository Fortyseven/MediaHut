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
        case 'webp':
            $thumb = new ThumbnailImage($path, $ext);
            return $thumb->getRenderer();
        case 'gif':
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
    <a href='$path'><figure class="entry">
        <figcaption class="filename">$base</figcaption>
        <div class='entry-object'>
            $rendered
        </div>
    </figure></a>
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

function renderReadmeMD($path)
{
    $readmemd_path = MEDIA_ROOT . $path . DIRECTORY_SEPARATOR . 'readme.md';

    if (file_exists($readmemd_path)) {
        $Parsedown = new Parsedown();

        $compiled = $Parsedown->text(file_get_contents($readmemd_path));
        echo "<div class='readmemd'>$compiled</div>";
    }

}
