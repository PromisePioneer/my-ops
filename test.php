<?php
exec('/usr/bin/chromium --headless --no-sandbox --disable-setuid-sandbox --disable-crash-reporter --disable-gpu --disable-software-rasterizer --disable-background-networking --disable-dev-shm-usage --disable-extensions --remote-debugging-port=9222 https://www.google.com', $output, $return_var);
var_dump($output);
var_dump($return_var);
?>
