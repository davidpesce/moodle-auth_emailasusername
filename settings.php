<?php
/**
 * Version details
 *
 * @package    auth_emailasusername
 * @copyright  2017 onwards David Pesce (http://exputo.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_emailasusername/pluginname', '',
        new lang_string('auth_emailasusername_description', 'auth_emailasusername')));
    
    $options = array(
        new lang_string('no'),
        new lang_string('yes'),
    );

    $settings->add(new admin_setting_configselect('auth_emailasusername/recaptcha',
        new lang_string('auth_emailasusername_recaptcha_key', 'auth_emailasusername'),
        new lang_string('auth_emailasusername_recaptcha', 'auth_emailasusername'), 0, $options));
    
    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin($this->name);
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
        get_string('auth_fieldlocks_help', 'auth'), false, false);
}