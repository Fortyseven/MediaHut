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

    $indexmd_path = MEDIA_ROOT . $userPath . DIRECTORY_SEPARATOR . 'index.md';

    if (file_exists($indexmd_path)) {
        $Parsedown = new Parsedown();

        $compiled = $Parsedown->text(file_get_contents($indexmd_path));
        echo "<div class='indexmd'>$compiled</div>";
    }

    echo pageFooter();
}
