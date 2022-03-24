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
