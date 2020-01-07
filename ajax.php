<?php

define('CID',false);
#define('READ_ONLY_SESSION',true);
require_once('../../include.php');
ModuleManager::load_modules();


if($_POST['action'] == 'deposit'){

    $itemID = $_POST['id'];
    $amount = $_POST['value'];
    
    Utils_RecordBrowserCommon::new_record("warehouseDeposit", array("date" => date("Y-m-d") , "amount" => $amount, "item" => $itemID ), array(), array());

    $rboItems  = new RBO_RecordsetAccessor("warehouseItems");
    $item = $rboItems->get_record($itemID);
    $newAmount = $item['amount'] + $amount;
    $item['amount'] = $newAmount;
    $item->save();

    $returned['value'] = $newAmount;

}

if($_POST['action'] == 'withdraw'){

    $itemID = $_POST['itemId'];
    $amount = $_POST['amount'];

    $rboItems  = new RBO_RecordsetAccessor("warehouseItems");
    $item = $rboItems->get_record($itemID);
    $newAmount = $item['amount'] - $amount;
    $item['amount'] = $newAmount;
    $item->save();

    Utils_RecordBrowserCommon::new_record("warehouseWithdraw", array( "date" => date("Y-m-d") , "amount" => $amount, "item" => $itemID, 'contact' => $_POST['userId'] ), 
                                                array(), array());
    $returned['value'] = $newAmount;
    $returned['itemId'] = $itemID;
}


echo json_encode($returned);