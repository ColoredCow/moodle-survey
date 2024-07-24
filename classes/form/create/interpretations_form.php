<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

class interpretations_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);
        $mform->addElement('html', '<div class="accordion">');
        $this->get_question_score_form($mform);
        $mform->addElement('html', '</div>');
        $this->get_form_action_button($mform);
    }
    
    // protected function get_question_score_section() {
    //     $section = '<div class="question-score-section">';
    //     $section .= '<div><label for="from" class="form-label">' . get_string('scorefrom', 'local_moodle_survey') . '</label>';
    //     $section .= '<input type="number" class="question-score" id="number" name="scorefrom[]" min="1" max="10"></div>';
    //     $section .= '<div><label for="to" class="form-label">' . get_string('scoreto', 'local_moodle_survey') . '</label>';
    //     $section .= '<input type="number" class="question-score" id="number" name="scoreto[]" min="1" max="10"></div>';
    //     $section .= '<div><label for="interpretedas" class="form-label">' . get_string('interpretedas', 'local_moodle_survey') . '</label>';
    //     $section .= '<input type="text" id="interpretedas" class="question-interpretedas" name="interpretedas[]" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"></div>';
    //     $section .= '</div>';
    //     return $section;
    // }

    protected function get_question_score_form($mform) {
        static $question_count = 1;
        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div id="question-category-template" class="accordion-item question-item-section" data-question-number"' . $question_count . '">');
        $mform->addElement('html', '<div class="accordion-header general-details-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>Question <span id="question-number">' . $question_count . '</span></h5>');
        $mform->addElement('html', '</div><div class="accordion-body">');
        $this->get_interpretation_question_field($mform);
        $mform->addElement('html', '</div></div>');
        $mform->addElement('html', '<div class="new-sections-container"></div>');
    }

    protected function get_interpretation_question_field($mform){
        $mform->addElement('text', 'interpretation_question[]', get_string('questionlabel', 'local_moodle_survey'), 'min="1" max="10" class="question-interpretedas"');
        $mform->setType('interpretation_question[]', PARAM_INT);
        $mform->addRule('interpretation_question[]', null, 'required', null, 'client');
        $mform->addElement('html', '<div class="new-option-section question-score-option-section" id="question-score-option-section">');
        $this->get_question_score_section($mform);
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div id="new-score-sections-container"></div>');
    }

    protected function get_question_score_section($mform){
        $mform->addElement('html', '<div class="question-score-section">');
        $mform->addElement('text', 'scorefrom[]', get_string('scorefrom', 'local_moodle_survey'), 'min="1" max="10"');
        $mform->setType('scorefrom[]', PARAM_INT);
        $mform->addRule('scorefrom[]', null, 'required', null, 'client');
        $mform->addElement('text', 'scoreto[]', get_string('scoreto', 'local_moodle_survey'), 'min="1" max="10"');
        $mform->setType('scoreto[]', PARAM_INT);
        $mform->addRule('scoreto[]', null, 'required', null, 'client');
        $mform->addElement('text', 'interpretedas[]', get_string('interpretedas', 'local_moodle_survey'), 'min="1" max="10" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"');
        $mform->setType('interpretedas[]', PARAM_INT);
        $mform->addRule('interpretedas[]', null, 'required', null, 'client');
        $this->get_add_new_option_button("new-score-and-associated-option", get_string('addnewscorebutton', 'local_moodle_survey'), $mform, "");
        $mform->addElement('html', '</div>');
    }

    public function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('savechanges'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $this->get_add_new_option_button("add-new-interpretation", get_string('newcategoryandinterpretation', 'local_moodle_survey'), $mform, "");
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }

    protected function get_add_new_option_button($buttonid, $buttonlabel, $mform, $containerid) {
        $mform->addElement('html', '<div id="' . $containerid . '">');
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $mform->addElement('html', '<button type="button" id="' . $buttonid . '" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . $buttonlabel . '</button>');
        $mform->addElement('html', '</div>');
    }

}
