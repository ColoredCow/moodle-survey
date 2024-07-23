<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class validity_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform = $this->_form;
        $mform->addElement('html', '<div class="form-section">');
        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="question">' . get_string('surveyvaliditystartdatelabel', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<input type="date" id="question" name="question" class="form-control">');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="question">' . get_string('surveyvalidityenddatelabel', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<input type="date" id="question" name="question" class="form-control">');
        $mform->addElement('html', '</div></div>');
    }
}
