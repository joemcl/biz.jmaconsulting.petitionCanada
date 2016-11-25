<?php

define('ENDPOINT', 'https://represent.opennorth.ca/');
define('CON', 'custom_1');
define('AFFN', 'custom_2');
define('TITLE', 'custom_3');

require_once 'ca.civix.php';

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function ca_civicrm_config(&$config) {
  _ca_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function ca_civicrm_xmlMenu(&$files) {
  _ca_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function ca_civicrm_install() {
  _ca_civix_civicrm_install();
  // Enable Campaign if not enabled.
  $components = CRM_Core_Component::getEnabledComponents();
  if (!array_key_exists('CiviCampaign', $components)) {
    $allComponents = CRM_Core_Component::getComponentIDs();
    $matches = array_intersect_key($allComponents, $components);
    $params = array_merge(array_keys($matches), array("CiviCampaign"));
    civicrm_api3('Setting', 'create', array(
      'enable_components' => $params,
    ));
  }
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function ca_civicrm_uninstall() {
  _ca_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function ca_civicrm_enable() {
  _ca_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function ca_civicrm_disable() {
  _ca_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function ca_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _ca_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function ca_civicrm_managed(&$entities) {
  // Create petition profile.
  $representatives = "<hr><div id='representatives'><i>Please enter your address above to view list of representatives here.</i></div><hr>";
  $email = "Please edit this field to enter the email text which will be frozen on petition page.";
  $entities[] = array(
    'module' => 'biz.jmaconsulting.represent.opennorth.ca',
    'name' => 'petitionprofile',
    'update' => 'never',
    'entity' => 'UFGroup',
    'params' => array(
      'title' => 'Petition Profile',
      'name' => 'petition_represent_extension',
      'is_active' => 1,
      'version' => 3,
      'sequential' => 1,
      'api.UFField.create' => array(
        array(
          'label' => 'First Name',
          'field_name' => 'first_name',
          'field_type' => 'Individual',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 1,
        ),
        array(
          'label' => 'Last Name',
          'field_name' => 'last_name',
          'field_type' => 'Individual',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 2,
        ),
        array(
          'label' => 'Email',
          'field_name' => 'email',
          'field_type' => 'Contact',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 3,
        ),
        array(
          'label' => 'Street Address',
          'field_name' => 'street_address',
          'field_type' => 'Contact',
          'is_active' => 1,
          'weight' => 4,
        ),
        array(
          'label' => 'City',
          'field_name' => 'city',
          'field_type' => 'Contact',
          'is_active' => 1,
          'weight' => 5,
        ),
        array(
          'label' => 'Country',
          'field_name' => 'country',
          'field_type' => 'Contact',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 6,
        ),
        array(
          'label' => 'State/Province',
          'field_name' => 'state_province',
          'field_type' => 'Contact',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 7,
        ),
        array(
          'label' => 'Postal Code',
          'field_name' => 'postal_code',
          'field_type' => 'Contact',
          'is_required' => TRUE,
          'is_active' => 1,
          'weight' => 8,
        ),
        array(
          'label' => 'Representatives',
          'field_name' => 'formatting_representatives',
          'field_type' => 'Formatting',
          'help_pre' => $representatives,
          'is_active' => 1,
          'weight' => 9,
        ),
        array(
          'label' => 'Representative Email',
          'field_name' => 'formatting_email',
          'field_type' => 'Formatting',
          'help_pre' => $email,
          'is_active' => 1,
          'weight' => 10,
        ),
      ),
    ),
  );
  // Create petition and add profile created above to petition.
  $entities[] = array(
    'module' => 'biz.jmaconsulting.represent.opennorth.ca',
    'name' => 'petitioncreate',
    'update' => 'never',
    'entity' => 'Survey',
    'params' => array(
      'title' => "Sign the Petition",
      'activity_type_id' => "Petition",
      'version' => 3,
      'sequential' => 1,
      'api.UFJoin.create' => array(
        'module' => "CiviCampaign",
        'entity_table' => "civicrm_survey",
        'entity_id' => '$value.id',
        'uf_group_id' => 'petition_represent_extension',
        'weight' => 2,
      ),
    ),
  );
  // Create group for signatures.
  $entities[] = array(
    'module' => 'biz.jmaconsulting.represent.opennorth.ca',
    'name' => 'groupcreate',
    'update' => 'never',
    'entity' => 'Group',
    'params' => array(
      'title' => "Petition Signatures",
      'name' => "petition_signatures",
      'group_type' => "Mailing List",
      'version' => 3,
      'sequential' => 1,
    ),
  );
  _ca_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function ca_civicrm_caseTypes(&$caseTypes) {
  _ca_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function ca_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _ca_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implementation of hook_civicrm_buildForm
 *
 */
function ca_civicrm_buildForm($formName, &$form) {
  if ($formName == "CRM_Campaign_Form_Petition_Signature") {
    CRM_Core_Region::instance('form-body')->add(array(
      'template' => 'Ca/Represent.tpl',
    ));
    if (isset($_COOKIE['signed_' . $form->_surveyId])) {
      CRM_Utils_System::setTitle("");
      return;
    }

    $template = NULL;
    $result = civicrm_api3('MessageTemplate', 'get', array(
      'sequential' => 1,
      'return' => array("msg_html"),
      'msg_title' => "OpenNorth",
    ));
    if ($result['count'] > 0) {
      $template = $result['values'][0]['msg_html'];
    }

    $form->add('hidden', 'representative_emails', NULL, array('readonly' => TRUE), FALSE);
    $form->add('hidden', 'representative_names', NULL, array('readonly' => TRUE), FALSE);
    $form->addWysiwyg('draft_email', ts('Email'), NULL);
    $form->add('checkbox', 'is_subscribe', ts('Do you wish to receive further communications?'));
    $defaults = array('is_subscribe' => TRUE);
    if ($template) {
      $defaults['draft_email'] = $template;
    }
    $form->setDefaults($defaults);
  }
}

/**
 * Implementation of hook_civicrm_postProcess
 *
 */
function ca_civicrm_postProcess($formName, &$form) {
  if ($formName == "CRM_Campaign_Form_Petition_Signature") {
    $targets = $submittedTargets = $master = array();
    // Add to signature group.
    if (CRM_Utils_Array::value('is_subscribe', $form->_submitValues)) {
      $group = civicrm_api3('GroupContact', 'create', array(
        'sequential' => 1,
        'group_id' => 'petition_signatures',
        'contact_id' => $form->_contactId,
        'status' => 'Added',
      ));
    }

    // Get fixed group targets.
    $fixed = civicrm_api3('GroupContact', 'get', array(
      'sequential' => 1,
      'return' => array("contact_id"),
      'group_id' => "Petition_Targets",
      'status' => "Added",
    ));

    if ($fixed['count'] > 0) {
      foreach ($fixed['values'] as $contact) {
        $targets[] = CRM_Contact_BAO_Contact::getPrimaryEmail($contact['contact_id']);
      }
    }
    if ($emails = CRM_Utils_Array::value('representative_emails', $form->_submitValues)) {
      $submittedTargets = explode(',', $emails);
    }
    $targets = array_filter($targets);
    // Remove duplicate emails and format.
    $master = implode(',', array_unique(array_merge($submittedTargets, $targets)));

    if (!empty($master)) {
      // Get frozen email text from profile.
      if ($localNames = CRM_Utils_Array::value('representative_names', $form->_submitValues)) {
        $message = $localNames . "<br/>";
      }
      $result = civicrm_api3('UFField', 'get', array(
        'sequential' => 1,
        'return' => array("help_pre"),
        'uf_group_id' => "petition_represent_extension",
        'field_type' => "Formatting",
        'field_name' => "formatting_email",
      ));

      if ($result['values'][0]['help_pre']) {
        $message .= $result['values'][0]['help_pre'];
      }

      // Get rest of email from petition form.
      if (CRM_Utils_Array::value('draft_email', $form->_submitValues)) {
        $message .= "<br/>";
        $message .= $form->_submitValues['draft_email'];
      }
      // Send the email.
      $params = array(
        'toEmail' => $master,
        'from' => $form->_submitValues['email-Primary'],
        'subject' => $form->petition['title'] . ' - Email',
        'html' => $message,
      );

      $sent = CRM_Utils_Mail::send($params);
      if ($sent) {
        $message .= "<br/>
          <b>Email also sent to representatives:</b> {$master}";
        // Create activity.
        $activityParams = array(
          'activity_name' => 'Email',
          'subject' => $form->petition['title'] . ' - Email',
          'status_id' => 'Completed',
          'activity_date_time' => date('YmdHis'),
          'source_contact_id' => $form->_contactId,
          'target_contact_id' => $form->_contactId,
          'assignee_contact_id' => $form->_contactId,
          'details' => $message,
          'version' => 3,
        );
        civicrm_api3('Activity', 'create', $activityParams);
      }
    }
  }
}
