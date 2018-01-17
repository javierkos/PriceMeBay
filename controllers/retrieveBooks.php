<?php

// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:pricemebay.database.windows.net,1433; Database = PriceMeBayDB", "javierkos", "koskos23!");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "javierkos@pricemebay", "pwd" => "{koskos23!}", "Database" => "PriceMeBayDB", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:pricemebay.database.windows.net,1433";

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
$sth = $conn->prepare('INSERT INTO books VALUES (?) , (?)');
if ($response->ack !== 'Failure') {
    foreach ($response->searchResult->item as $item) {
        $t = mysqli_real_escape_string($conn,$item->title);
        $i = mysqli_real_escape_string($conn,$item->itemId);
        /*$sth->bindParam(1, $t);
        $sth->bindParam(2, $i);
        $sth->execute();*/
        $conn->query("INSERT INTO books VALUES '$t','$i'");
        $sql = $mysqli->prepare("INSERT user_id, username, password, salt FROM users WHERE username = ? LIMIT 1");
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