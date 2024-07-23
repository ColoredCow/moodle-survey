<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class audience_access_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add elements for audience and access
        $mform->addElement('textarea', 'audience', get_string('audience', 'local_moodle_survey'));
        $mform->setType('audience', PARAM_TEXT);

        $mform->addElement('textarea', 'access', get_string('access', 'local_moodle_survey'));
        $mform->setType('access', PARAM_TEXT);
    }

    public function targetAudienceForm() {
        echo "Target Audience Form";
    }

    public function accessToResponseForm() {
        echo "Access To Response Form";
    }

    public function assignToSchoolForm() {
        echo "Assign To School Form";
    }
}
