<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$p = App\Models\Page::find(4);
$html = (string)($p->content ?? '');
preg_match_all('/<(p|div|figure|img)[^>]*>/i', $html, $m);
foreach ($m[0] as $t) { echo $t, "\n"; }
