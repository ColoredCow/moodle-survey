<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class validity_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add elements for validity
        $mform->addElement('text', 'validity', get_string('validity', 'local_moodle_survey'));
        $mform->setType('validity', PARAM_NOTAGS);

        $this->add_action_buttons();
    }
}
