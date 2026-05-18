<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
  $count = App\Models\Page::count();
  echo "Page count: $count\n";
  $pages = App\Models\Page::query()->orderByDesc('id')->limit(3)->get(['id','title','slug','layout','content']);
  foreach ($pages as $p) {
      echo "===== PAGE {$p->id} | {$p->title} | {$p->slug} | layout={$p->layout} =====\n";
      $html = (string)$p->content;
      echo substr($html,0,500),"\n\n";
  }
} catch (Throwable $e) {
  echo 'ERR: '.$e->getMessage()."\n";
}
