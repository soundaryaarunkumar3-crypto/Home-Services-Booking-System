<?php
echo "OK - admin folder reachable. Files in folder:<br><pre>";
print_r(scandir(__DIR__));
echo "</pre>";
