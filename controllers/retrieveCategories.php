<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

//$conn = new mysqli("tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "koskos23!");
$conn = new PDO("sqlsrv:server = tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "GLP23ASq2");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
error_reporting(-1);
ini_set('display_errors', 'On');

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

$request = new Types\GetCategoriesRequestType();

$request->RequesterCredentials = new Types\CustomSecurityHeaderType();
$request->RequesterCredentials->eBayAuthToken = $config['production']['authToken'];

$request->DetailLevel = ['ReturnAll'];

$request->OutputSelector = [
    'CategoryArray.Category.CategoryID',
    'CategoryArray.Category.CategoryParentID',
    'CategoryArray.Category.CategoryLevel',
    'CategoryArray.Category.CategoryName'
];

$response = $service->getCategories($request);

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

$stmt = $conn->prepare("INSERT INTO categories (name,parent_id,ebay_id) VALUES (?, ?, ?,?)");
if ($response->Ack !== 'Failure') {
    foreach ($response->CategoryArray->Category as $category) {
        if ($category->CategoryParentID[0] != NULL){
            $stmt->bindParam(1, $category->CategoryName);
            $stmt->bindParam(2, $category->CategoryParentID[0]);
            $stmt->bindParam(3, $category->CategoryID);
            $stmt->execute();  
        }
    }
}