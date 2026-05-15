<?php
$base=__DIR__;
require $base.'/vendor/autoload.php';
$app=require $base.'/bootstrap/app.php';
$kernel=$app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$rows=App\Models\Category::query()->where('type','news')->select('id','name','slug')->orderBy('id')->get();
foreach($rows as $r){echo $r->id.'|'.$r->name.'|'.$r->slug.PHP_EOL;}
