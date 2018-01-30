<?php
error_reporting(-1);
ini_set('display_errors', 'On');

require __DIR__.'/../vendor/autoload.php';

$config = require 'configuration.php';

//$conn = new mysqli("tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "koskos23!");
$conn = new PDO("sqlsrv:server = tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "GLP23ASq2");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
error_reporting(-1);
ini_set('display_errors', 'On');

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
$numMainCat = 0;
$mainCategories = array();
if ($response->Ack !== 'Failure') {
    foreach ($response->SuggestedCategoryArray->SuggestedCategory as $category) {
        #$level = checkLevel($category->Category->CategoryID,$conn);
        getCatStack($category->Category->CategoryID);
        $elements[$count]['catper'] = $category->PercentItemFound;
        $elements[$count]['catLevel'] = $level;
        $elements[$count]['catName'] = $category->Category->CategoryName;
        $elements[$count]['catId'] = $category->Category->CategoryID;
        $elements[$count]['catParId'] = $category->Category->CategoryParentID[0];

        $count = $count + 1;
    }
    #echo json_encode($elements);
}

function checkLevel($catId,$conn){
    $stmt = $conn->prepare("SELECT * FROM categories WHERE ebay_id = ?");
    $stmt->bindParam(1, $catId);
    $stmt->execute();
    $row = $stmt->fetch();
    return $row['level'];
}

function getCatStack($catId,$conn){
    $stmt = $conn->prepare("
        DECLARE @curId INT = 0,
                @parentId INT = ?,
                @name VARCHAR(100) = '',
                @cats VARCHAR(MAX) = '';

        WHILE @curId != @parentId
        BEGIN
        SELECT @curId=ebay_id,@parentId = parent_id,@name = name + ',' + @name FROM categories WHERE ebay_id = @parentId;
        END;
        SELECT value as 'catstack' FROM STRING_SPLIT(@name, ',');");
    $stmt->bindParam(1, $catId);
    $stmt->execute();
    $result = $stmt->fetchAll();
    echo $result;
    return $result;
}