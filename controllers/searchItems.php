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
    'credentials' => $config['sandbox']['credentials'],
    'globalId'    => Constants\GlobalIds::GB,
    'authToken' => $config['sandbox']['authToken'],
    'apiVersion' => '1.13.0',
    'sandbox' => true
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
if ($response->ack !== 'Failure') {
    foreach ($response->searchResult->item as $item) {
        printf(
            "(%s) %s: %s %.2f\n",
            $item->itemId,
            $item->title,
            $item->sellingStatus->currentPrice->currencyId,
            $item->sellingStatus->currentPrice->value
        );
        echo $item->title;
    }
}