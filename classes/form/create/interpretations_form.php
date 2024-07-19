<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class interpretations_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add elements for interpretations
        $mform->addElement('textarea', 'interpretations', get_string('interpretations', 'local_moodle_survey'));
        $mform->setType('interpretations', PARAM_TEXT);

        $this->add_action_buttons();
    }
}
