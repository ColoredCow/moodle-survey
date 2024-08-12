<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

class interpretations_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);

        $questioncategories = $this->_customdata['questioncategories'];
        $categoryindex = 0;
        foreach ($questioncategories as $category) {
            $mform->addElement('html', '<div class="accordion">');
            $this->get_question_score_form($mform, $categoryindex, $category);
            $mform->addElement('html', '</div>');
            $categoryindex++;
        }
        $this->get_form_action_button($mform);
    }

    protected function get_question_score_form($mform, $index, $category) {
        $questioncount = $index + 1;

        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div id="question-category-template" class="accordion-item question-item-section" data-question-number"' . $questioncount . '">');
        $mform->addElement('html', '<div class="accordion-header accordion-header-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>QUESTION CATEGORY <span id="question-number">' . $questioncount . '</span></h5>');
        $mform->addElement('html', '</div><div class="accordion-body">');
        $this->get_interpretation_question_field($mform, $index, $category);
        $mform->addElement('html', '</div></div>');
        $mform->addElement('html', '<div class="new-sections-container"></div>');
    }

    protected function get_interpretation_question_field($mform, $index, $category){
        $mform->addElement('text', null, get_string('questioncategorylabel', 'local_moodle_survey'), 'value="'. $category->label . '" disabled class="question-interpretedas"');
        $mform->addElement('text', 'interpretation[' . $index . ']', get_string('questioncategorylabel', 'local_moodle_survey'), 'readonly value="'. $category->id . '" class="d-none question-interpretedas"');
        $mform->setType('interpretation[' . $index . ']', PARAM_INT);
        $mform->addRule('interpretation[' . $index . ']', null, 'required', null, 'client');
        
        $mform->addElement('html', '<div class="new-option-section question-score-option-section">');
        $this->get_question_score_section($mform, $index, $category);
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div id="new-score-sections-container"></div>');
    }

    protected function get_question_score_section($mform, $index, $category){
        $mform->addElement('html', '<div class="question-score-section">');
        
        $mform->addElement('text', 'scorefrom[' . $index . ']', get_string('scorefrom', 'local_moodle_survey'));
        $mform->setType('scorefrom[' . $index . ']', PARAM_INT);
        $mform->addRule('scorefrom[' . $index . ']', null, 'required', null, 'client');
        $mform->setDefault('scorefrom[' . $index . ']', $category->score_from);
        
        $mform->addElement('text', 'scoreto[' . $index . ']', get_string('scoreto', 'local_moodle_survey'));
        $mform->setType('scoreto[' . $index . ']', PARAM_INT);
        $mform->addRule('scoreto[' . $index . ']', null, 'required', null, 'client');
        $mform->setDefault('scoreto[' . $index . ']', $category->score_to);
        
        $mform->addElement('text', 'interpretedas[' . $index . ']', get_string('interpretedas', 'local_moodle_survey'), 'size="50" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"');
        $mform->setType('interpretedas[' . $index . ']', PARAM_TEXT);
        $mform->addRule('interpretedas[' . $index . ']', null, 'required', null, 'client');
        $mform->setDefault('interpretedas[' . $index . ']', $category->interpreted_as);

        $this->get_add_new_option_button(get_string('newrangeandinterpretation', 'local_moodle_survey'), $mform, "");
        $mform->addElement('html', '</div>');
    }

    public function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('savechanges'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }

    protected function get_add_new_option_button($buttonlabel, $mform, $containerid) {
        $mform->addElement('html', '<div id="' . $containerid . '">');
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $mform->addElement('html', '<button type="button" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . $buttonlabel . '</button>');
        $mform->addElement('html', '</div>');
    }
}
