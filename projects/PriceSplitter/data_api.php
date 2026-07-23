<?php
header('Content-Type: application/json');

// location of the json "database"
$dataFile = __DIR__ . '/batches.json';

// read body
$raw = file_get_contents('php://input');
$payload = json_decode($raw, true);

// load existing
$batches = [];
if (file_exists($dataFile)) {
    $txt = file_get_contents($dataFile);
    $batches = json_decode($txt, true);
    if (!is_array($batches)) $batches = [];
}

if (!isset($payload['mode'])) {
    echo json_encode(['ok'=>false,'error'=>'no mode']);
    exit;
}

if ($payload['mode'] === 'saveBatch') {

    // sanitize inputs
    $batchName = trim($payload['batchName'] ?? '');
    $batchDesc = trim($payload['batchDesc'] ?? '');
    $itemsIn  = $payload['items'] ?? [];
    $totalsIn = $payload['totals'] ?? [];

    // re-run math on server for trust
    $SPLIT_RULES = [
        "generic" => [ "jacob"=>1/3,  "ryan"=>1/3,  "marlin"=>1/3  ],
        "jacob"   => [ "jacob"=>0.66, "ryan"=>0.17, "marlin"=>0.17 ],
        "ryan"    => [ "jacob"=>0.17, "ryan"=>0.66, "marlin"=>0.17 ],
        "marlin"  => [ "jacob"=>0.17, "ryan"=>0.17, "marlin"=>0.66 ],
    ];

    $cleanItems = [];
    $jacobTotal = 0;
    $ryanTotal  = 0;
    $marlinTotal= 0;
    $allTotal   = 0;

    foreach ($itemsIn as $it) {
        $name = trim($it['name'] ?? '');
        $price = floatval($it['price'] ?? 0);
        $who = $it['payerType'] ?? 'generic';
        if ($name === '' || $price <= 0) continue;

        // clamp who
        if (!isset($SPLIT_RULES[$who])) $who = "generic";

        $rule = $SPLIT_RULES[$who];
        $jacobTotal += $price * $rule['jacob'];
        $ryanTotal  += $price * $rule['ryan'];
        $marlinTotal+= $price * $rule['marlin'];
        $allTotal   += $price;

        $cleanItems[] = [
            'name' => $name,
            'price' => round($price, 2),
            'payerType' => $who,
        ];
    }

    $savedBatch = [
        'timestamp' => date('Y-m-d H:i:s'),
        'batchName' => $batchName,
        'batchDesc' => $batchDesc,
        'totals' => [
            'jacob'  => round($jacobTotal, 2),
            'ryan'   => round($ryanTotal, 2),
            'marlin' => round($marlinTotal, 2),
            'all'    => round($allTotal, 2),
        ],
        'items' => $cleanItems,
    ];

    // append and persist
    $batches[] = $savedBatch;
    file_put_contents($dataFile, json_encode($batches, JSON_PRETTY_PRINT));

    echo json_encode([
        'ok' => true,
        'savedBatch' => $savedBatch
    ]);
    exit;
}

// unknown mode
echo json_encode(['ok'=>false,'error'=>'bad mode']);
exit;
