<?php

$filename = $argv[1];
$attempts = (int) ($argv[2] ?? 50);

if (empty($attempts)) {
    var_dump($attempts);
    var_dump($argv);
    exit;
}

$results = [];

foreach (hash_algos() as $algo) {
    $intermediateResults = [];

    for ($i=0; $i < $attempts; $i++) {
        $s = microtime(true);
        $h = hash_file($algo, $filename);
        $intermediateResults[] = microtime(true) - $s;
    }

    $results[] = [
        'a' => $algo,
        't' => array_sum($intermediateResults) / $attempts,
        'h' => $h,
    ];
}

usort($results, function ($a, $b) {
    if ($a['t'] === $b['t']) {
        return 0;
    }

    return ($a['t'] < $b['t']) ? -1 : 1;
});

print_r($results);
