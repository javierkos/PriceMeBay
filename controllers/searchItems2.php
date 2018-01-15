<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

$config = require 'configuration.php';
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Merchandising\Services;
use \DTS\eBaySDK\Merchandising\Types;
use \DTS\eBaySDK\Merchandising\Enums;

$service = new Services\MerchandisingService([
    'credentials' => $config['production']['credentials'],
    'globalId'    => Constants\GlobalIds::GB,
    'apiVersion' => '1.5.0',
    'sandbox' => false
]);

try {
    $request = new Types\GetSimilarItemsRequest();

} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

$request->maxResults = 10;
$request->itemId = '352039451777';

$response = $service->getSimilarItems($request);

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
        $count = $count + 1;
    }
    
    echo json_encode($elements);
}