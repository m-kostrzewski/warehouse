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
            $this->set_module_variable("warehouseID", $_REQUEST['warehouseID']);
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
            $rs = new RBO_RecordsetAccessor('warehouseWithdraw');
            $rb = $rs->create_rb_module ( $this );
            $this->display_module ( $rb);

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