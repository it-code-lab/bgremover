<?php
const SUPPORTED = [
  'usd','inr','eur','gbp','aud','cad','jpy','sgd','nzd','zar','brl','mxn','php','aed','hkd','myr','chf','sek','dkk','nok'
];

function getRates() {
    $cacheFile = __DIR__ . '/currency_rates_cache.json';
    $cacheTTL = 86400; // 1 day in seconds

    // Use cached data if available and not expired
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $cacheTTL) {
        $cachedData = file_get_contents($cacheFile);
        $data = json_decode($cachedData, true);
        if ($data && isset($data['rates'])) {
            return $data['rates'];
        }
    }

    //DND - Commented out live API call for now
    
    // Fallback: fetch live rates
    // $apiKey = 'https://api.exchangerate.host/latest?base=USD';
    // $response = @file_get_contents($apiKey);
    // $data = json_decode($response, true);

    // if ($data && isset($data['rates'])) {
    //     file_put_contents($cacheFile, json_encode($data)); // cache it
    //     return $data['rates'];
    // }

    // Fallback to default rates if API fails
    return [
        'usd' => 1,
        'eur' => 0.92,
        'inr' => 83,
        'gbp' => 0.78,
        'aud' => 1.5,
        'cad' => 1.37,
        'jpy' => 155,
        'cny' => 7.2,
        'brl' => 5.1,
        'mxn' => 18,
        'zar' => 18.5,
        'sgd' => 1.35,
        'chf' => 0.9,
        'hkd' => 7.8,
        'sek' => 10.6,
        'nok' => 11.2,
        'nzd' => 1.7,
        'aed' => 3.67,
        'try' => 32,
        'thb' => 36
    ];
}
?>
