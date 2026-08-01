<?php

if (empty($_SERVER['APP_KEY']) && empty(getenv('APP_KEY'))) {
    putenv('APP_KEY=' . 'base64:' . base64_encode(random_bytes(32)));
}

require __DIR__ . '/../public/index.php';
