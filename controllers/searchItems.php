<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

$config = require 'configuration.php';
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;

$service = new Services\FindingService([
    'credentials' => $config['production']['credentials'],
    'globalId'    => Constants\GlobalIds::GB,
    'apiVersion' => '1.13.0',
    'sandbox' => false
]);

$request = new Types\FindItemsByKeywordsRequest();

$request->keywords = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);

$response = $service->findItemsByKeywords($request);

if (isset($response->errorMessage)) {
    foreach ($response->errorMessage->error as $error) {
        printf(
            "%s: %s\n\n",
            $error->severity=== Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
            $error->message
        );
    }
}
$elements = array();
$count = 0;
if ($response->ack !== 'Failure') {
    foreach ($response->searchResult->item as $item) {
        $elements[$count]['itemId'] = $item->itemId;
        $elements[$count]['title'] = $item->title;
        $elements[$count]['itemId'] = $item->itemId;
        $elements[$count]['currency'] = $item->sellingStatus->currentPrice->currencyId;
        $elements[$count]['price'] = $item->sellingStatus->currentPrice->value;
        $elements[$count]['pic'] = $item->galleryURL;
        $elements[$count]['productId'] = $item->galleryURL;
        $count = $count + 1;
    }
    
    echo json_encode($elements);
}