<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/moodle_survey/lib/customformslib.php');

class audience_access_form extends \customformlib {

    protected $formlabel;

    public function __construct($formlabel = null) {
        $this->formlabel = $formlabel;
        parent::__construct($formlabel);
    }

    public function definition() {
        switch ($this->formlabel) {
            case 'targetaudience':
                $this->target_audience_form();
                break;
            case 'accesstoresponse':
                $this->access_to_response_form();
                break;
            case 'assigntoschool':
                $this->assign_to_school_form();
                break;
        }
    }

    private function target_audience_form() {
        return $this->get_checkbox_input_fields(get_string('targetaudiencevalues', 'local_moodle_survey'));
    }

    private function access_to_response_form() {
        return $this->get_checkbox_input_fields(get_string('accesstoresponsevalues', 'local_moodle_survey'));
    }

    private function assign_to_school_form() {
        $section = '';
        foreach(get_string('assigntoschools', 'local_moodle_survey') as $key => $state_of_school) {
            $section .= $this->get_select_input_fields($key, $state_of_school);
        }
        return $section;
    }

    private function get_select_input_fields($key, $label) {
        $mform = $this->_form;
        $class = ($key === 'select_school') ? 'form-group select-schools-section' : 'form-group';
        $mform->addElement('html', '<div class="' . $class . '">');
        $mform->addElement('html', '<label for="' . $key . '">' . $label . '</label>');
        $mform->addElement('html', '<select id="' . $key . '" name="' . $key . '" class="form-control" required>');
        $mform->addElement('html', '<option value="0">' . get_string('inactive', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '<option value="1">' . get_string('active', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '</select>');
        $mform->addElement('html', '</div>');
    }

    private function get_checkbox_input_fields($sections_values){
        $mform = $this->_form;
        $mform->addElement('html', '<div class="audience-access-form">');
        foreach($sections_values as $key => $accesstoresponsevalue) {
            $mform->addElement('html', '<div class="checkbox-section">');
            $mform->addElement('html', '<input type="checkbox" id="' . $key . '" name="' . $key . '" class="form-control">');
            $mform->addElement('html', '<label for="question" class="checkbox-label">' . $accesstoresponsevalue . '</label>');
            $mform->addElement('html', '</div>');
        }
        $mform->addElement('html', '</div>');
    }
}
