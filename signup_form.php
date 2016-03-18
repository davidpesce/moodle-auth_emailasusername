<?php

/**
 * Authentication Plugin: Email Username Authentication
 *
 * @copyright  2016 onwards David Pesce (http://exputo.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package auth_emailusername
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot . '/user/editlib.php');

class login_signup_form extends moodleform {
    function definition() {
        global $USER, $CFG;
        $mform = $this->_form;
        $mform->addElement('header', 'createuserandpass', get_string('auth_emailusername_signupheader'), '');

        $mform->addElement('text', 'username', get_string('auth_emailusername_email'), 'maxlength="100" size="25"');
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username', get_string('missingusername'), 'required', null, 'client');

        $mform->addElement('text', 'email', get_string('auth_emailusername_emailconfirm'), 'maxlength="100" size="25"');
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username', get_string('missingusername'), 'required', null, 'client');

        if (!empty($CFG->passwordpolicy)){
            $mform->addElement('static', 'passwordpolicyinfo', '', print_password_policy());
        }

        $mform->addElement('passwordunmask', 'password', get_string('password'), 'maxlength="32" size="12"');
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', get_string('missingpassword'), 'required', null, 'client');

        $namefields = useredit_get_required_name_fields();
        foreach ($namefields as $field) {
            $mform->addElement('text', $field, get_string($field), 'maxlength="100" size="30"');
            $mform->setType($field, PARAM_NOTAGS);
            $stringid = 'missing' . $field;
            if (!get_string_manager()->string_exists($stringid, 'moodle')) {
                $stringid = 'required';
            }
            $mform->addRule($field, get_string($stringid), 'required', null, 'client');
        }

        profile_signup_fields($mform);
        if ($this->signup_captcha_enabled()) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('security_question', 'auth'), array('https' => $CFG->loginhttps));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
            $mform->closeHeaderBefore('recaptcha_element');
        }
        if (!empty($CFG->sitepolicy)) {
            $mform->addElement('header', 'policyagreement', get_string('policyagreement'), '');
            $mform->setExpanded('policyagreement');
            $mform->addElement('static', 'policylink', '', '<a href="'.$CFG->sitepolicy.'" onclick="this.target=\'_blank\'">'.get_String('policyagreementclick').'</a>');
            $mform->addElement('checkbox', 'policyagreed', get_string('policyaccept'));
            $mform->addRule('policyagreed', get_string('policyagree'), 'required', null, 'client');
        }
        // buttons
        $this->add_action_buttons(true, get_string('createaccount'));
    }
    function definition_after_data(){
        $mform = $this->_form;
        $mform->applyFilter('username', 'trim');
        // Trim required name fields.
        foreach (useredit_get_required_name_fields() as $field) {
            $mform->applyFilter($field, 'trim');
        }
    }
    function validation($data, $files) {
        global $CFG, $DB;
        $errors = parent::validation($data, $files);
        $authplugin = get_auth_plugin($CFG->registerauth);
        if ($DB->record_exists('user', array('username'=>$data['username'], 'mnethostid'=>$CFG->mnet_localhost_id))) {
            $errors['username'] = get_string('usernameexists');
        } else {
            //check allowed characters
            if ($data['username'] !== core_text::strtolower($data['username'])) {
                $errors['username'] = get_string('usernamelowercase');
            } else {
                if ($data['username'] !== clean_param($data['username'], PARAM_USERNAME)) {
                    $errors['username'] = get_string('invalidusername');
                }
            }
        }
        //check if user exists in external db
        //TODO: maybe we should check all enabled plugins instead
        if ($authplugin->user_exists($data['username'])) {
            $errors['username'] = get_string('usernameexists');
        }

        //validate email address
        if (! validate_email($data['email'])) {
            $errors['email'] = get_string('invalidemail');
        } else if ($DB->record_exists('user', array('email'=>$data['email']))) {
            $errors['email'] = get_string('emailexists').' <a href="/login/forgot_password.php">'.get_string('newpassword').'?</a>';
        }

        if ($data['username'] != $data['email']) {
            $errors['email'] = get_string('invalidemail');
        }
        if (!isset($errors['email'])) {
            if ($err = email_is_not_allowed($data['email'])) {
                $errors['email'] = $err;
            }
        }
        $errmsg = '';
        if (!check_password_policy($data['password'], $errmsg)) {
            $errors['password'] = $errmsg;
        }
        if ($this->signup_captcha_enabled()) {
            $recaptcha_element = $this->_form->getElement('recaptcha_element');
            if (!empty($this->_form->_submitValues['recaptcha_challenge_field'])) {
                $challenge_field = $this->_form->_submitValues['recaptcha_challenge_field'];
                $response_field = $this->_form->_submitValues['recaptcha_response_field'];
                if (true !== ($result = $recaptcha_element->verify($challenge_field, $response_field))) {
                    $errors['recaptcha'] = $result;
                }
            } else {
                $errors['recaptcha'] = get_string('missingrecaptchachallengefield');
            }
        }
        // Validate customisable profile fields. (profile_validation expects an object as the parameter with userid set)
        $dataobject = (object)$data;
        $dataobject->id = 0;
        $errors += profile_validation($dataobject, $files);
        return $errors;
    }
    /**
     * Returns whether or not the captcha element is enabled, and the admin settings fulfil its requirements.
     * @return bool
     */
    function signup_captcha_enabled() {
        global $CFG;
        $authplugin = get_auth_plugin($CFG->registerauth);
        return !empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey) && $authplugin->is_captcha_enabled();
    }
}