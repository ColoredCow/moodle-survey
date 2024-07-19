<?php
namespace local_moodle_survey\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class edit_survey_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $survey = $this->_customdata['survey'];

        $mform->addElement('hidden', 'id', $survey->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('surveyname', 'local_moodle_survey'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $survey->name);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('surveydescription', 'local_moodle_survey'));
        $mform->setType('description', PARAM_TEXT);
        $mform->setDefault('description', $survey->description);

        $statusoptions = [
            'active' => get_string('active', 'local_moodle_survey'),
            'inactive' => get_string('inactive', 'local_moodle_survey')
        ];
        $mform->addElement('select', 'status', get_string('surveystatus', 'local_moodle_survey'), $statusoptions);
        $mform->setDefault('status', $survey->status);

        

        $this->add_action_buttons();
    }
}
