<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

class questions_scores_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);

        // Retrieve questions and categories
        $data = $this->_customdata;
        $surveyquestions = $data['surveyquestions'];

        $initialindex = 0;

        if (sizeof($surveyquestions) <= 0) {
            $data = null;
            $this->add_question_section($mform, $initialindex, null, null);
        }

        // For edit the survey questions and score form
        foreach ($surveyquestions as $index => $data) {
            $question = $data['question'];
            $category = $data['category'];
            $options = $data['options'];
            $this->add_question_section($mform, $index, $question, $category, $options);
        }

        $this->get_form_action_button($mform);
    }

    protected function add_question_section($mform, $index, $question, $category, $options = null) {
        $mform->addElement('html', '<div class="accordion" id="accordion">');
        $this->get_question_score_form($mform, $index, $question, $category, $options);
        $mform->addElement('html', '</div>');
    }

    protected function get_question_score_form($mform, $index, $question, $category, $options) {
        $questionposition = $index;
        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div class="question-item-section" data-question-number="' . $questionposition . '">');
        $mform->addElement('html', '<div class="accordion-header general-details-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>Question <span class="question-number">' . $questionposition . '</span></h5>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="accordion-body question-score-form">');
        $this->get_survey_question_field($mform, $index, $question);
        $this->get_question_category_section($mform, $index, $category);
        foreach ($options as $option) {
            $this->get_question_score_section($mform, $index, $question, $option);
        }
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }

    protected function get_question_score_section($mform, $index, $question, $option) {
        $mform->addElement('html', '<div class="associated-option-section">');
        
        $mform->addElement('text', 'question[' . $index . '][score][0]', get_string('score', 'local_moodle_survey'), 'size="15" min="1" max="10" class=""');
        $mform->setType('question[' . $index . '][score][0]', PARAM_INT);
        $mform->addRule('question[' . $index . '][score][0]', null, 'required', null, 'client');
        $mform->setDefault('question[' . $index . '][score][0]', $option->score);
        
        $mform->addElement('text', 'question[' . $index . '][option][0]', get_string('associatedoption', 'local_moodle_survey'), 'size="50" placeholder="' . get_string('interpretedasplaceholder', 'local_moodle_survey') . '"');
        $mform->setType('question[' . $index . '][option][0]', PARAM_NOTAGS);
        $mform->addRule('question[' . $index . '][option][0]', null, 'required', null, 'client');
        $mform->setDefault('question[' . $index . '][option][0]', $option->option_text);
        
        $mform->addElement('html', '</div>');
    }

    protected function get_survey_question_field($mform, $index, $question){
        $mform->addElement('text', 'question[' . $index . '][text]', get_string('questionlabel', 'local_moodle_survey'), 'size="50" class=""');
        $mform->setType('question[' . $index . '][text]', PARAM_NOTAGS);
        $mform->addRule('question[' . $index . '][text]', null, 'required', null, 'client');
        
        // Set default value if it exists
        if (isset($question->text)) {
            $mform->setDefault('question[' . $index . '][text]', $question->text);
        }
    }

    protected function get_question_category_section($mform, $index, $category) {
        $mform->addElement('html', '<div class="question-category-section"><div class="question-category-selection">');
        $options = [];
        $allquestioncategories = $this->_customdata['questioncategories'];
        foreach ($allquestioncategories as $category) {
            $options[$category->id] = $category->label;
        }
        $mform->addElement('select', 'question[' . $index . '][category_id]', get_string('questioncategory', 'local_moodle_survey'), $options);
        $mform->setType('question[' . $index . '][category_id]', PARAM_INT);
        $mform->addRule('question[' . $index . '][category_id]', null, 'required', null, 'client');
        
        if (isset($category->id)) {
            $mform->setDefault('question[' . $index . '][category_id]', $category->id);
        }
        
        $mform->addElement('html', '</div></div>');
    }

    protected function get_add_new_option_button($buttonid, $buttonlabel, $mform, $containerid) {
        $mform->addElement('html', '<div id="' . $containerid . '">');
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
        $mform->addElement('html', '<button type="button" id="' . $buttonid . '" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . $buttonlabel . '</button>');
        $mform->addElement('html', '</div>');
    }

    public function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('submit', 'local_moodle_survey'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $this->get_add_new_option_button("add-new-question-button", get_string('addnewquestion', 'local_moodle_survey'), $mform, "");
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }

}
