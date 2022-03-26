?>
<?php

// more will happen here; probably
class Data
{
    public function __construct()
    {
        if (!file_exists(DATA_ROOT)) {
            mkdir(DATA_ROOT);
        }
        if (!file_exists(THUMBS_ROOT)) {
            mkdir(THUMBS_ROOT);
        }
    }
}
