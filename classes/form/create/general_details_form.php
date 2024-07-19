<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class general_details_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('surveyname', 'local_moodle_survey'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('surveydescription', 'local_moodle_survey'));
        $mform->setType('description', PARAM_TEXT);

        $statusoptions = [
            'active' => get_string('active', 'local_moodle_survey'),
            'inactive' => get_string('inactive', 'local_moodle_survey')
        ];
        $mform->addElement('select', 'status', get_string('surveystatus', 'local_moodle_survey'), $statusoptions);
        $mform->setDefault('status', 'active');


        $this->add_action_buttons();
    }
}
