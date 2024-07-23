<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/customformslib.php");

class interpretations_form extends \customformlib {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('html', '<div class="form-section">');
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="question">' . get_string('questionlabel', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<input type="text" id="question" name="question" class="form-control" placeholder="' . get_string('questionplaceholder', 'local_moodle_survey') . '">');
        $mform->addElement('html', '<div class="new-option-section question-score-option-section" id="question-score-option-section">');
        $mform->addElement('html', $this->get_question_score_section());
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div id="new-score-sections-container"></div>');
        $mform->addElement('html', '<button type="button" id="new-score-and-associated-option" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . get_string('addnewscorebutton', 'local_moodle_survey') . '</button>');
        $mform->addElement('html', '</div></div>');
    }
    
    protected function get_question_score_section() {
        $section = '<div class="question-score-section">';
        $section .= '<div><label for="from" class="form-label">' . get_string('scorefrom', 'local_moodle_survey') . '</label>';
        $section .= '<input type="number" class="question-score" id="number" name="scorefrom[]" min="1" max="10"></div>';
        $section .= '<div><label for="to" class="form-label">' . get_string('scoreto', 'local_moodle_survey') . '</label>';
        $section .= '<input type="number" class="question-score" id="number" name="scoreto[]" min="1" max="10"></div>';
        $section .= '<div><label for="interpretedas" class="form-label">' . get_string('interpretedas', 'local_moodle_survey') . '</label>';
        $section .= '<input type="text" id="interpretedas" class="question-interpretedas" name="interpretedas[]" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"></div>';
        $section .= '</div>';
        return $section;
    }
}
