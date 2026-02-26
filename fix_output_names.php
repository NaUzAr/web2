<?php
// Fix output_name in device_outputs to match MQTT keys from device

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\DeviceOutput;

$map = [
    'sts_air_input' => 'st_air',
    'sts_mixing' => 'st_mix',
    'sts_pompa' => 'st_pmp',
    'sts_fan' => 'st_fa',
    'sts_misting' => 'st_mis',
    'sts_lampu' => 'st_lam',
    'sts_dosing' => 'st_dos',
    'sts_ph_up' => 'st_ph_u',
    'sts_air_baku' => 'st_bak',
    'sts_air_pupuk' => 'st_ppk',
    'sts_ph_down' => 'st_ph_d',
];

foreach ($map as $old => $new) {
    $count = DeviceOutput::where('output_name', $old)->update(['output_name' => $new]);
    echo "$old -> $new: $count updated\n";
}

echo "\nDone! Verifying:\n";
$outputs = DeviceOutput::where('device_id', 34)->get(['id', 'output_name', 'output_label', 'current_value']);
foreach ($outputs as $o) {
    echo "  [{$o->id}] {$o->output_name} ({$o->output_label}) = {$o->current_value}\n";
}
