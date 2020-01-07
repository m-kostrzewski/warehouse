<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class warehouseInstall extends ModuleInstall {


    public function install() {
        Base_ThemeCommon::install_default_theme($this->get_type());
        Base_LangCommon::install_translations($this->get_type());

        $table = new warehouse_warehouses();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new warehouse_Items();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new warehouse_Withdraw();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new warehouse_Settings();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        $table = new warehouse_Deposit();
        $success = $table->install();
        $table->add_access('view', 'ACCESS:employee');
        $table->add_access('edit', 'ACCESS:employee');
        $table->add_access('delete', 'ADMIN');
        $table->add_access('add', 'ACCESS:employee');

        return true;
    }

    public function uninstall() {

        $table = new warehouse_warehouses();
        $success = $table->uninstall();
        
        $table = new warehouse_Items();
        $success = $table->uninstall();

        $table = new warehouse_Withdraw();
        $success = $table->uninstall();

        $table = new warehouse_Settings();
        $success = $table->uninstall();

        $table = new warehouse_Deposit();
        $success = $table->uninstall();

        return true;
    }

    public function requires($v) {
        // Returns list of modules and their versions, that are required to run this module
        return array(); 
    }
    
    public function info() { // Returns basic information about the module which will be available in the epesi Main Setup
        return array (
            'Author' => 'Mateusz Kostrzewski',
            'License' => 'MIT 1.0',
            'Description' => '' 
        );
    }
    
    public function version() {
        return array('1.0'); 
    }
    
    public function simple_setup() { // Indicates if this module should be visible on the module list in Main Setup's simple view
        return array (
                'package' => __( 'warehouse' ),
                'version' => '1.0' 
        ); // - now the module will be visible as "HelloWorld" in simple_view
    }


}