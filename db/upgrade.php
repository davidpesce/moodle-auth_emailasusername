<?php
/**
 * Version details
 *
 * @package    auth_emailasusername
 * @copyright  2017 onwards David Pesce (http://exputo.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
/**
 * Function to upgrade auth_emailasusername.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_auth_email_upgrade($oldversion) {
    global $CFG, $DB;

    if ($oldversion < 2017020700) {
        // Convert info in config plugins from auth/email to auth_email.
        $DB->set_field('config_plugins', 'plugin', 'auth_emailasusername', array('plugin' => 'auth/emailasusername'));
        upgrade_plugin_savepoint(true, 2017020700, 'auth', 'emailasusername');
    }
    return true;
}