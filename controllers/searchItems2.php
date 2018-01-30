<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

$config = require 'configuration.php';

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

$service = new Services\TradingService([
    'credentials' => $config['production']['credentials'],
    'siteId'      => Constants\SiteIds::GB,
    'apiVersion' => '1043',
    'sandbox' => false
]);

$request = new Types\GetSuggestedCategoriesRequestType();

$request->Query = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);

$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['production']['authToken'];

$request->OutputSelector = [
    'CategoryArray.Category.CategoryID',
    'CategoryArray.Category.CategoryParentID',
    'CategoryArray.Category.CategoryLevel',
    'CategoryArray.Category.CategoryName'
];

$response = $service->getSuggestedCategories($request);

if (isset($response->Errors)) {
    foreach ($response->Errors as $error) {
        printf(
            "%s: %s\n%s\n\n",
            $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
            $error->ShortMessage,
            $error->LongMessage
        );
    }
}

$elements = array();
$count = 0;

if ($response->Ack !== 'Failure') {

    foreach ($response->CategoryArray->Category as $category) {
        $elements[$count]['catLevel'] = $category->CategoryLevel;
        $elements[$count]['catName'] = $category->CategoryName;
        $elements[$count]['catId'] = $category->CategoryID;
        $elements[$count]['catParId'] = $category->CategoryParentID[0];
        $count = $count + 1;
    }
    echo json_encode($elements);
}