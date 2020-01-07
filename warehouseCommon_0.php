<?php
defined("_VALID_ACCESS") || die('Direct access forbidden');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class warehouseCommon extends ModuleCommon {

    public static function menu() {
		return array(__('Module') => array('__submenu__' => 1, __('warehouse') => array(
	    	'__icon__'=>'umowa.png','__icon_small__'=>'umowa.png'
			)));
    }

	public static function quick_menu() {
		return array('Quick menu test'=>array('action'=>'ble'));
    }
    
    public static function user_settings() {
        return array(__("warehouse settings")=> 'settings');
     }

     public static function autoselect_contact($str, $crits, $format_callback) {
      $str = explode(' ', trim($str));
      foreach ($str as $k=>$v)
          if ($v) {
              $v = "%$v%";
              $crits = Utils_RecordBrowserCommon::merge_crits($crits, array('~first_name'=>$v,'|~last_name'=>$v));
          }
      $recs = Utils_RecordBrowserCommon::get_records('contact', $crits, array(), array('last_name'=>'ASC'), 10);
      $ret = array();
      foreach($recs as $v) {
          $ret[$v['id']."__".$v['last_name']." ".$v['first_name']] = call_user_func($format_callback, $v, true);
      }
      return $ret;
  }
  public static function contact_format_contact($record, $nolink=false){

      $ret = $record['last_name']." ".$record['first_name'];
      return $ret;
  }

}