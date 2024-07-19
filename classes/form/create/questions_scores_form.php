<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class questions_scores_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        // Add an HTML editor for questions
        $mform->addElement('editor', 'questions_editor', get_string('questions', 'local_moodle_survey'), null, array(
            'maxfiles' => EDITOR_UNLIMITED_FILES,
            'noclean' => true,
        ));
        $mform->setType('questions_editor', PARAM_RAW);

        // Add a text element for scores
        $mform->addElement('text', 'scores', get_string('scores', 'local_moodle_survey'));
        $mform->setType('scores', PARAM_INT);

        $this->add_action_buttons();
    }
}
