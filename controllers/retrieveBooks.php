<?php

// PHP Data Objects(PDO) Sample Code:

//$conn = new mysqli("tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "koskos23!");
$conn = mysqli_init();
mysqli_real_connect($conn,"pricemebay.database.windows.net","javierkos","koskos23!","PriceMeBayDB", 3306);

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

$request = new Types\FindItemsAdvancedRequest();

//$request->keywords = filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_STRING);

$request->categoryId = ['267'];

$itemFilter = new Types\ItemFilter();
$itemFilter->value[] = 'Auction';
$request->itemFilter[] = $itemFilter;

$response = $service->findItemsAdvanced($request);



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
$sql = $conn->prepare("INSERT INTO books VALUES ?,?");
if ($response->ack !== 'Failure') {
    foreach ($response->searchResult->item as $item) {
        $t = (string)$item->title;
        $i = (string)$item->itemId;
        $sql->bind_param(1, $t);
        $sql->bind_param(2, $i);
        $sql->execute();   
        //$sql = $mysqli->prepare("INSERT user_id, username, password, salt FROM users WHERE username = ? LIMIT 1");
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