<?php

require_once 'exportchangecurrentemployerlenght.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function exportchangecurrentemployerlenght_civicrm_config(&$config) {
  _exportchangecurrentemployerlenght_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function exportchangecurrentemployerlenght_civicrm_xmlMenu(&$files) {
  _exportchangecurrentemployerlenght_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function exportchangecurrentemployerlenght_civicrm_install() {
  _exportchangecurrentemployerlenght_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function exportchangecurrentemployerlenght_civicrm_uninstall() {
  _exportchangecurrentemployerlenght_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function exportchangecurrentemployerlenght_civicrm_enable() {
  _exportchangecurrentemployerlenght_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function exportchangecurrentemployerlenght_civicrm_disable() {
  _exportchangecurrentemployerlenght_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function exportchangecurrentemployerlenght_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _exportchangecurrentemployerlenght_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function exportchangecurrentemployerlenght_civicrm_managed(&$entities) {
  _exportchangecurrentemployerlenght_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function exportchangecurrentemployerlenght_civicrm_caseTypes(&$caseTypes) {
  _exportchangecurrentemployerlenght_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function exportchangecurrentemployerlenght_civicrm_angularModules(&$angularModules) {
_exportchangecurrentemployerlenght_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function exportchangecurrentemployerlenght_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _exportchangecurrentemployerlenght_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function exportchangecurrentemployerlenght_civicrm_preProcess($formName, &$form) {

}

*/

function exportchangecurrentemployerlenght_civicrm_buildForm($formName, &$form) {
  if('CRM_Export_Form_Map' == $formName){
    CRM_Core_Session::setStatus('Als je het veld "Huidige werkgever" wilt gebruiken en de volledige naam wilt hebben (128 karakters lang) dan moet u de het veld "Interne contactnummer" toevoegen !', 'Huidige werkgever', 'alert');
  }
}

function exportchangecurrentemployerlenght_civicrm_export(&$exportTempTable, &$headerRows, &$sqlColumns, &$exportMode){
  if(isset($sqlColumns['current_employer']) and isset($sqlColumns['civicrm_primary_id'])){
    $query = "ALTER TABLE ".$exportTempTable." MODIFY current_employer VARCHAR(128)";     
    CRM_Core_DAO::executeQuery($query);
    
    $query = "SELECT * FROM $exportTempTable";
    $dao = CRM_Core_DAO::executeQuery($query);
    while ($dao->fetch()) {
      try {
        $params = array(
        'version' => 3,
        'sequential' => 1,
        'contact_id' => $dao->civicrm_primary_id,
      );
      $result = civicrm_api('Contact', 'getsingle', $params);

      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not find setting, '
          . 'error from Setting getsingle: '.$ex->getMessage());
      }
      
      if(!$result['is_error']){
        if(isset($result['current_employer']) and !empty($result['current_employer'])){
          $query = "UPDATE ".$exportTempTable." SET current_employer = '" . mysql_real_escape_string($result['current_employer']) . "' WHERE civicrm_primary_id = '" . $result['contact_id'] . "'";     
          CRM_Core_DAO::executeQuery($query);
        }
      }
    }
  }
}