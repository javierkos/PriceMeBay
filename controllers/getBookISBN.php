<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require __DIR__.'/../vendor/autoload.php';

use \DTS\eBaySDK\Constants;
use \DTS\eBaySDK\Trading\Services;
use \DTS\eBaySDK\Trading\Types;
use \DTS\eBaySDK\Trading\Enums;


function getISBN($refId){

    $config = require 'configuration.php';
    $service = new Services\TradingService([
        'credentials' => $config['production']['credentials'],
        'authToken'   => 'AgAAAA**AQAAAA**aAAAAA**gk5cWg**nY+sHZ2PrBmdj6wVnY+sEZ2PrA2dj6ADkoqmCpaHowydj6x9nY+seQ**jxkEAA**AAMAAA**Dz5I25WbPcRIjjjVrkiwlo+J2duxmVbOPTW3KeEmJ6irT7bWnXYGU0G5pOTy1Lj6qYsCuyyOUt9TO1Wj2qsimzDC8IbG6MvzzzNNkfKa2o2+Doosy5skfOQ1ZVKHrQV3wWu+4N3A1uWJMCtM5wwA216NlOnrZ3eoCiGp2j+kCUdjAe+nW9IGtFal6MCu2vjGpevrpn5irkH0KjRkwIxddMWFrEwTO/KcpVGL4aVVMI2tfgaaJuZyuLMC4J6eDYbtg2pDbSBDmPTVkEcnOd9foOO6ZYfhGzEOSrwfGZdKq2qH/e5etC7qAJpcu0XMoMacHcH0ClSjvxGjnoi1Mg4ANJnu4L7Zk4DFssDcQJ4d1AaNbuf7YKXzC1nkNdWor7Cm8ebJvCG9YvxffUwkNvKqvUOPmlx+jxL1IQEl35Y4sDEOGUm7aSImkK7iGY2VG4XHIXjqIdxju3Rs2oZGJQwLY3l7HllMV9JP0tl0t/CuZZHSsiCuxJjjGPI8frbC/ivmItA+c7FH5hVpKQsS6sU4YGT+QW2WuLy9SLmxvcKflM2WlXIL5tKe4E0LGbODrP2X+b/5Lz4ZHmL6qWXvUHTtAsTF1Gpk6bAqzBq8MnQAwRk8MwFXZhv2N6vqsgffNEVjNb31jyg1Iu2Cnb+H0278lo7oSUVnWWqwvnPoChGOg4ricRky2dSB1rL7VWb0uuM/MsgTJbHsBebca5i0mzQzammmXXSJVrTgNuxNOQQPlFOq7v6xBMdkqehbUXD7U6gT',
        'siteId'    => Constants\SiteIds::GB
    ]);

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
        return $item->ProductListingDetails->ISBN;
    }

}