<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

$config = require 'configuration.php';
/**
 * The namespaces provided by the SDK.
 */
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Finding\Services;
use \DTS\eBaySDK\Finding\Types;
use \DTS\eBaySDK\Finding\Enums;
/**
 * Create the service object.
 */
$service = new Services\FindingService([
    'credentials' => $config['production']['credentials'],
    'globalId'    => Constants\GlobalIds::GB,
    'apiVersion' => '1.13.0',
    'sandbox' => false
]);
/**
 * Create the request object.
 */
$request = new Types\FindItemsByKeywordsRequest();
/**
 * Assign the keywords.
 */
$request->keywords = 'Harry Potter';
/**
 * Send the request.
 */
$response = $service->findItemsByKeywords($request);
/**
 * Output the result of the search.
 */
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
        $elements[$count]['title'] = $item->title;
        $elements[$count]['itemId'] = $item->itemId;
    }
    echo json_encode($elements);
}