<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Setting::updateOrCreate(['key' => 'seo_default_og_image'], ['value' => 'site/test-og.png', 'group' => 'seo']);
$row = \App\Models\Setting::where('key', 'seo_default_og_image')->first();
var_dump($row?->key, $row?->value, $row?->group);
