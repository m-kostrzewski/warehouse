<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');

class warehouse_warehouses extends RBO_Recordset {

    function table_name() { 
        return 'warehouses';
    }
    function fields() {

        $name = new RBO_Field_Text(_M("name"));
        $name->set_visible()->set_length(64);

        return array($name); 
    }
}

class warehouse_Items extends RBO_Recordset {

    function table_name() {

        return 'warehouseItems';

    }
    function fields() { 

        $warehouse = new RBO_Field_Select(_M("warehouse"));
        $warehouse->from('warehouses')->fields('name')->set_visible()->set_required();

        $name = new RBO_Field_Text(_M("name"));
        $name->set_visible()->set_length(64);

        $amount = new RBO_Field_Integer(_M("amount"));
        $amount->set_visible()->set_required();

        $img = new RBO_Field_Text(_M("image product"));
        $img->set_visible()->set_length(64);

        return array($warehouse,$name,$amount,$img); 
    }

}

class warehouse_Withdraw extends RBO_Recordset {

    function table_name() { 

        return 'warehouseWithdraw';

    }
    function fields() { 

        $item = new RBO_Field_Select(_M("item"));
        $item->from('warehouseItems')->fields('name')->set_visible()->set_required();

        $amount = new RBO_Field_Integer(_M("amount"));
        $amount->set_required()->set_visible();

        $contact = new RBO_Field_Select(_M('contact'));
        $contact->from('contact')->set_crits_callback("warehouseCommon::driversOnly")->fields('last_name','first_name')->set_visible()->set_required();

        $withdrawDate = new RBO_Field_Date(_M("date"));
        $withdrawDate->set_required()->set_visible();

        return array($item,$amount,$contact,$withdrawDate); 
    }
    
}

class warehouse_Deposit extends RBO_Recordset {

    function table_name() { 

        return 'warehouseDeposit';

    }
    function fields() { 

        $item = new RBO_Field_Select(_M("item"));
        $item->from('warehouseItems')->fields('name')->set_visible()->set_required();

        $amount = new RBO_Field_Integer(_M("amount"));
        $amount->set_required()->set_visible();

        $depositDate = new RBO_Field_Date(_M("date"));
        $depositDate->set_required()->set_visible();

        return array($item,$amount,$depositDate); 
    }
    
}


class warehouse_Settings extends RBO_Recordset {

    function table_name() { 

        return 'warehouseSettings';

    }
    function fields() { 

        $warehouse = new RBO_Field_Select(_M("warehouse"));
        $warehouse->from('warehouses')->fields('name')->set_visible()->set_required();

        $item = new RBO_Field_Select(_M("item"));
        $item->from('warehouseItems')->fields('name')->set_visible()->set_required();

        $amountAlert = new RBO_Field_Integer(_M('amount alert'));
        $amountAlert->set_visible()->set_required();


        return array($warehouse,$item,$amountAlert); 
    }
    
}