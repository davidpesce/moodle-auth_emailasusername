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
function xmldb_auth_emailasusername_upgrade($oldversion) {
    global $CFG, $DB;

    if ($oldversion < 2024102300) {
        // No database changes needed for Moodle 4.5+ compatibility update.
        // This version adds privacy API compliance and coding standards improvements.
        upgrade_plugin_savepoint(true, 2024102300, 'auth', 'emailasusername');
    }

    return true;
}