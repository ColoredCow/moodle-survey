<?php
defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");

class customformlib extends \moodleform {
    protected $cancel;
    protected $cancelurl;
    protected $submitlabel;

    public function __construct($cancel = true, $cancelurl = null, $submitlabel = null) {
        $this->cancel = $cancel;
        $this->cancelurl = $cancelurl;
        $this->submitlabel = $submitlabel;
        parent::__construct($cancel, $submitlabel);
    }

    public function definition() {
        return $this->add_custom_action_buttons_helper($this->cancel, $this->cancelurl, $this->submitlabel);
    }

    public function add_custom_action_buttons_helper($cancel = true, $cancelurl = null,$submitlabel = null) {
        $mform = $this->_form;
        $mform->addElement('html', '<div class="form-action-buttons">');
        if ($cancel) {
            $url = new \moodle_url($cancelurl ?? '/local/moodle_survey/manage_survey.php');
            $mform->addElement('html', '<a href="' . $url . '" class="custom-action-btn custom-cancel-button">' . get_string('cancel') . '</a>');
        }
        $submitlabel = $submitlabel ?? get_string('savechanges');
        $mform->addElement('html', '<button type="submit" class="custom-action-btn custom-submit-button">' . $submitlabel . '</button>');
        $mform->addElement('html', '</div>');
    }

}