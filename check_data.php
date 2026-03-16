<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ud = App\Models\UserDevice::find(2);
if($ud) {
    $d = $ud->device;
    echo "Table Name: " . $d->table_name . "\n";
    echo "Count: " . \DB::table($d->table_name)->count() . "\n";
    $first = \DB::table($d->table_name)->orderBy('recorded_at', 'asc')->first();
    echo "First: " . ($first ? $first->recorded_at : 'null') . "\n";
    $last = \DB::table($d->table_name)->orderBy('recorded_at', 'desc')->first();
    echo "Last: " . ($last ? $last->recorded_at : 'null') . "\n";
} else {
    echo "UserDevice ID 2 not found\n";
}
