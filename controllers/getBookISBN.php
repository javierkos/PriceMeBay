<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require __DIR__.'/../vendor/autoload.php';
use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;

$service = new Services\TradingService([
    'credentials' => $config['production']['credentials'],
    'globalId'    => Constants\GlobalIds::GB,
    'apiVersion' => '1043',
    'sandbox' => false
]);

function getISBN($refId){
    $request = new Types\GetItemRequestType() ;
    $request->ItemID = $refId;
    $request->DetailLevel = ['ReturnAll'];
    $response = $service->getItem($request);

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

    if ($response->Ack !== 'Failure') {
        $item = $response->Item;
        return $item->productIdentifier->ISBN;
    }

}