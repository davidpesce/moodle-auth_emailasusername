<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_emailasusername', language 'en'.
 *
 * @package   auth_emailasusername
 * @copyright  2016 onwards David Pesce (http://exputo.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_emailasusernamedescription'] = '<p>Email-based self-registration enables a user to create their own account via a \'Create new account\' button on the login page. The user then receives an email containing a secure link to a page where they can confirm their account. Future logins just check the username and password against the stored values in the Moodle database.</p><p>Note: In addition to enabling the plugin, email-based self-registration must also be selected from the self registration drop-down menu on the \'Manage authentication\' page.</p>';
$string['auth_emailasusernamenoemail'] = 'Tried to send you an email but failed!';
$string['auth_emailasusernamerecaptcha'] = 'Adds a visual/audio confirmation form element to the sign-up page for email self-registering users. This protects your site against spammers and contributes to a worthwhile cause. See http://www.google.com/recaptcha for more details.';
$string['auth_emailasusernamerecaptcha_key'] = 'Enable reCAPTCHA element';
$string['auth_emailasusernamesettings'] = 'Settings';
$string['auth_emailasusername_email'] = 'Email address';
$string['auth_emailasusername_emailconfirm'] = 'Email address (again)';
$string['auth_emailasusername_signupheader'] = 'Fill in the fields below';
$string['auth_emailasusername_emailmissing'] = 'Fill in the fields below';
$string['pluginname'] = 'Email-based self-registration - Username is email address';
