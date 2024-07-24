<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

class questions_scores_form extends \moodleform {
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

    protected function get_question_score_form($mform) {
        static $question_count = 1;
        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div id="question-template" class="accordion-item question-item-section" data-question-number"' . $question_count . '">');
        $mform->addElement('html', '<div class="accordion-header general-details-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>Question <span id="question-number">' . $question_count . '</span></h5>');
        $mform->addElement('html', '</div><div class="accordion-body question-score-form">');
        $this->get_survey_question_field($mform);
        $this->get_question_category_section($mform);
        $mform->addElement('html', '<div class="new-option-section question-score-option-section" id="question-score-option-section">');
        $this->get_question_score_section($mform);
        $mform->addElement('html', '</div></div></div>');
        $mform->addElement('html', '<div class="new-sections-container"></div>');
    }
    
    protected function get_question_score_section($mform) {
        $mform->addElement('html', '<div class="question-score-section">');
        $mform->addElement('text', 'questionscore[]', get_string('score', 'local_moodle_survey'), 'min="1" max="10" class=""');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="associated-option-section">');
        $mform->addElement('text', 'associatedoption[]', get_string('associatedoption', 'local_moodle_survey'), 'maxlength="100" size="30" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"');
        $mform->setType('associatedoption[]', PARAM_NOTAGS);
        $mform->addRule('associatedoption[]', null, 'required', null, 'client');
        $mform->addElement('html', '</div>');
    }

    protected function get_survey_question_field($mform){
        $mform->addElement('text', 'question', get_string('questionlabel', 'local_moodle_survey'), 'maxlength="100" size="30" class=""');
        $mform->setType('question', PARAM_NOTAGS);
        $mform->addRule('question', null, 'required', null, 'client');
    }

    protected function get_question_category_section($mform) {
        $mform->addElement('html', '<div class="question-category-section"><div class="question-category-selection">');
        $options = [];
        $questioncategories = [
            ['id' => 1, 'label' => 'Category 1'],
            ['id' => 2, 'label' => 'Category 2'],
            ['id' => 3, 'label' => 'Category 3'],
        ];
        foreach ($questioncategories as $category) {
            $options[$category['id']] = $category['label'];
        }
        $mform->addElement('select', 'question_category_id', get_string('questioncategory', 'local_moodle_survey'), $options);
        $mform->setType('question_category_id', PARAM_INT);
        $mform->addRule('question_category_id', null, 'required', null, 'client');
        $mform->addElement('html', '</div></div>');
    }

    protected function get_add_new_option_button($buttonid, $buttonlabel, $mform, $containerid) {
        $mform->addElement('html', '<div id="' . $containerid . '">');
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $mform->addElement('html', '<button type="button" id="' . $buttonid . '" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . $buttonlabel . '</button>');
        $mform->addElement('html', '</div>');
    }

    public function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('savechanges'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $this->get_add_new_option_button("add-new-question-button", get_string('addnewquestion', 'local_moodle_survey'), $mform, "");
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }
}
