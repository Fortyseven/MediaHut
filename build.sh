#!/bin/bash

npx css-minify --file src/style.css -o src

cat src/stage0.php src/html_header.html src/stage1.php src/html_footer.html src/stage2.php src/style.min.css src/stage3.php >build/index.php

cat src/core/*.php >>build/index.php

echo "?><?php main();" >>build/index.php
