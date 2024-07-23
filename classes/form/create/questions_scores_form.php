<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/customformslib.php");

class questions_scores_form extends \customformlib {
    public function definition() {
        $mform = $this->_form;
        $mform->addElement('html', '<div class="form-section">');
        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="question">' . get_string('questionlabel', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<input type="text" id="question" name="question" class="form-control" placeholder="' . get_string('questionplaceholder', 'local_moodle_survey') . '">');
        $mform->addElement('html', '<div class="new-option-section question-score-option-section" id="question-score-option-section">');
        $mform->addElement('html', $this->get_question_score_section());
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div id="new-sections-container"></div>');
        $mform->addElement('html', $this->get_add_new_option_button(get_string('addnewscorebuttonid', 'local_moodle_survey'), get_string('addnewscorebutton', 'local_moodle_survey')));
        $mform->addElement('html', $this->get_question_category_section());
        $mform->addElement('html', '</div></div>');
    }
    
    protected function get_question_score_section() {
        $section = '<div class="question-score-section">';
        $section .= '<div><label for="from" class="form-label">' . get_string('score', 'local_moodle_survey') . '</label>';
        $section .= '<input type="number" id="number" class="question-score" name="score[]" min="1" max="10"></div>';
        $section .= '<div class="associated-option-section"><label for="associatedoption" class="form-label">' . get_string('associatedoption', 'local_moodle_survey') . '</label>';
        $section .= '<input type="text" id="associatedoption" class="question-associatedoption" name="associatedoption[]" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"></div>';
        $section .= '</div>';
        return $section;
    }

    protected function get_question_category_section() {
        $section = '<div class="question-category-section">';
        $section .= '<div class="question-category-selection"><label for="category" class="form-label">' . get_string('questioncategory', 'local_moodle_survey') . '</label>';
        $section .= '<select id="id_name" name="status" class="form-control question-score-category-selection" required>';
        $section .= '<option value="0">' . get_string('inactive', 'local_moodle_survey') . '</option>';
        $section .= '<option value="1">' . get_string('active', 'local_moodle_survey') . '</option>';
        $section .= '</select></div>';
        $section .= '<div id="question-category-selection"></div>';
        $section .= $this->get_add_new_option_button(get_string('addnewcategoryid', 'local_moodle_survey'), get_string('addnewcategory', 'local_moodle_survey'));
        $section .= '</div>';
        return $section;
    }

    protected function get_add_new_option_button($buttonid, $buttonlabel) {
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $button = '<button type="button" id="' . $buttonid . '" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . $buttonlabel . '</button>';
        return $button;
    }
}
