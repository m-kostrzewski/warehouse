<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class warehouse extends Module {

    public function body()
    {
        Base_ThemeCommon::install_default_theme($this->get_type());
        Base_LangCommon::install_translations($this->get_type());

        if($this->get_module_variable("view") == null){
            $this->set_module_variable("view","main");
        }
        $theme = $this->init_module('Base/Theme');
        //$form = $this->init_module('Libs/QuickForm');
        //viev prepare block
        $viewType = '';
        if(isset($_REQUEST['view'])){
            $viewType = $_REQUEST['view'];
        }
        if($_REQUEST['view'] == "main"){
            $this->set_module_variable("view","main");
        }
        if($_REQUEST['view'] == "showWithdraws"){
            $this->set_module_variable("view","showWithdraws");
        }
        if($viewType == "warehouseView"){
            $this->set_module_variable("view","warehouseView");
            if($_REQUEST['warehouseID']){
                $this->set_module_variable("warehouseID", $_REQUEST['warehouseID']);
            }
        }

        //view display block
        $view = $this->get_module_variable("view");
        if($view == 'main'){
            $warehouses = Utils_RecordBrowserCommon::get_records("warehouses",array(),array(),array());
            $list = array();
            foreach($warehouses as $warehouse){
                $list[$warehouse['id']]['name'] = $warehouse['name'];
                $list[$warehouse['id']]['link'] = $this->create_href(array("view" => "warehouseView", "warehouseID" => $warehouse['id']) );
            }

            Base_ActionBarCommon::add(
                'add',
                __("Dodaj magazyn"), 
                Utils_RecordBrowserCommon::create_new_record_href('warehouses',$def = array(),$id='none'),
                    null,
                    1
            );

            $theme->assign("warehouseList",$list);
        }
        if($view == "showWithdraws"){
            Base_ActionBarCommon::add(
                'back',
                __("Back"), 
                $this->create_href(array("view" => "warehouseView")),
                    null,
                    1
            );
            $form = $this->init_module('Libs/QuickForm');
            $rboRecord = new RBO_RecordsetAccessor("warehouseItems");
            $records = $rboRecord->get_records(array('warehouse'=>$this->get_module_variable("warehouseID")),array(),array());
            $items = array(''=>'---');
            foreach($records as $record){
                $items[$record['id']] = $record['name'];
            }
            $crits = array();
            $fcallback = array('warehouseCommon','contact_format_contact');
            $form->addElement('autoselect', 'contactSelect', 'Wybierz pracownika', array(),
                array(array('warehouseCommon','autoselect_contact'), array($crits, $fcallback)), $fcallback);
            $form->addElement("datepicker","from", "Od");
            $form->addElement("datepicker","to", "Do");
            $form->addElement("select","item", "Przedmiot", $items);
            $form->addElement("submit","save", "Filtruj",array("class" => "button"));
            $form->display_as_row();

            $filters = array('_active'=>1 );
            if($form->validate()){
                $values =$form->exportValues();
                if($values['from'] != '' && count($values['from'])){
                    $filters['>=date'] = $values['from'];
                }
                if($values['to'] != '' && count($values['to'])){
                    $filters['<=date'] = $values['to'];
                }
                if($values['item'] != '' && count($values['item'])){
                    $filters['item'] = $values['item'];
                }
                if($values['contactSelect'] != '' && count($values['contactSelect'])){
                    $filters['contact'] = $values['contactSelect'];
                }
                $this->set_module_variable("filters",$filters);
            }
            if($this->get_module_variable("filters")){
                $filters = $this->get_module_variable("filters");
            }
            $rboWithdraws = new RBO_RecordsetAccessor("warehouseWithdraw");
            $pages = $rboWithdraws->get_records_count($filters);
            if($pages > 25){
                $pages = $pages / 25;
                $pages = floor($pages);
                if($_REQUEST['page'])
                    $this->set_module_variable("currentPage",$_REQUEST['page']);
                else
                    $this->set_module_variable("currentPage",'0');

                $page = $this->get_module_variable("currentPage");
                $max = $pages;
                $pagerStart = $page - 3;
                $pageEnd = $page + 3;
                if($pagerStart < 0){
                    $pagerStart = 0;
                }
                if($pageEnd >= $max){
                    $pageEnd = $max;
                }
    
                for($start = $pagerStart; $start <= $pageEnd;$start++){
                    $value = $start;
                    $display = $start + 1;
                    $link = $this->create_href(array("page" => $value));
                    if($value == $page){
                        $pageList[] =  "<a class='links' style='color:#000000;font-weight:bold;' $link>$display</a>";
                    }else{  
                        $pageList[] =  "<a class='links' $link>$display</a>";
                    }
                }
                $filters = $this->get_module_variable("filters");
            }
            $theme->assign("pages",$pageList);

            $records = $rboWithdraws->get_records($filters,array(),array("date" => "DESC"),array( 'numrows'=>25 ,'offset'=>$page * 25));
            foreach($records as $record){
                $record['item'] = $record->get_val("item");
                $record['contact'] = $record->get_val("contact");
            }
            $theme->assign("records",$records);
            $theme->display($view);
            load_js($this->get_module_dir()."js/jquery.tablesorter.min.js");
            load_js($this->get_module_dir()."jquery.tablesorter.widgets.min.js");
            load_js($this->get_module_dir().'js/analis.js');
            Base_ThemeCommon::load_css('warehouse','theme.default.min');
            eval_js("	jq(function(){
                jq('.data-table').tablesorter({
                    widgets        : ['zebra', 'columns'],
                    usNumberFormat : false,
                    sortReset      : true,
                    sortRestart    : true
                });
            });");
            load_js($this->get_module_dir().'/main.js');

        }
        if($view == 'warehouseView'){
            Base_ActionBarCommon::add(
                'back',
                __("Back"), 
                $this->create_href(array("view" => "main")),
                    null,
                    1
            );
            $action = 'withdraw';
            if($_REQUEST['action'] == 'deposit'){
                Base_ActionBarCommon::add(
                    'add',
                    __("Wydaj pracownikowi"), 
                    $this->create_href(array("action" => "withdraw")),
                        null,
                        1
                );
                $action = 'deposit';
            }
            else{ 
                Base_ActionBarCommon::add(
                    'add',
                    __("Dostawa towaru"), 
                    $this->create_href(array("action" => "deposit")),
                        null,
                        1
                );
              
                Base_ActionBarCommon::add(
                    'add',
                    __("Dodaj przedmiot"), 
                    Utils_RecordBrowserCommon::create_new_record_href('warehouseItems',$def=array('warehouse'=>$this->get_module_variable("warehouseID") ),$id='none'),
                        null,
                        1
                );

                Base_ActionBarCommon::add(
                    'add',
                    __("Pokaż zamówienia"), 
                    $this->create_href(array("view" => "showWithdraws")),
                        null,
                        1
                );
               
                $action = 'withdraw';
                $crits = array();
                $form = $this->init_module('Libs/QuickForm');
                $fcallback = array('warehouseCommon','contact_format_contact');
                $form->addElement('autoselect', 'contactSelect', 'Wybierz pracownika', array(),
                    array(array('warehouseCommon','autoselect_contact'), array($crits, $fcallback)), $fcallback);
                $form->toHtml();
                $form->assign_theme('my_form', $theme);

            }
            $theme->assign("action", $action);
            $items = Utils_RecordBrowserCommon::get_records("warehouseItems", array('warehouse' => $this->get_module_variable("warehouseID") ),array(),array());
            $theme->assign("items", $items);

            $warehouse = Utils_RecordBrowserCommon::get_record("warehouses",$this->get_module_variable("warehouseID"));
            $theme->assign("warehouseName", $warehouse['name']);

        }
        if($view != 'showWithdraws'){
            Base_ThemeCommon::load_css('warehouse','default');
            
            $theme->display($view);
            load_js($this->get_module_dir().'/main.js');
        }
    }
    public function settings()
    {
        $tabbed_browser = $this->init_module('Utils/TabbedBrowser');
        $tabbed_browser->set_tab(__('set notifications'), array($this, 'settingsAlerts'));
        $tabbed_browser->set_tab(__('Zestawienie'), array($this, 'reports'));
        $tabbed_browser->set_tab(__('Raport z tuczy'), array($this, 'analise'));
        $this->display_module($tabbed_browser);
    }

}