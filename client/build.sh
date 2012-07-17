md2html docs/docs.md > docs/docs.php
md2html docs/about.md > docs/about.php
md2html docs/indexIntro.md > docs/indexIntro.php

pandoc docs/docs.md -o docs/docs.php
pandoc docs/about.md -o docs/about.php
pandoc docs/indexIntro.md -o docs/indexIntro.php
