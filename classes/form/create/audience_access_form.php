<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class audience_access_form extends \moodleform {

    public function definition() {
        $this->targetAudienceForm();
    }

    public function targetAudienceForm() {
        $mform = $this->_form;
        $mform->addElement('html', '<div class="audience-access-form">');
        $mform->addElement('html', '<div class="checkbox-section">');
        $mform->addElement('html', '<input type="checkbox" id="' . get_string('studentsid', 'local_moodle_survey') . '" name="' . get_string('studentsid', 'local_moodle_survey') . '" class="form-control">');
        $mform->addElement('html', '<label for="question">' . get_string('students', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="checkbox-section"><input type="checkbox" id="' . get_string('teachersid', 'local_moodle_survey') . '" name="' . get_string('teachersid', 'local_moodle_survey') . '" class="form-control">');
        $mform->addElement('html', '<label for="question">' . get_string('teachers', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '</div></div>');
    }

    public function accessToResponseForm() {
        echo "Access To Response Form";
    }

    public function assignToSchoolForm() {
        echo "Assign To School Form";
    }
}
