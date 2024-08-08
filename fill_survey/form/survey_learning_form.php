<?php
namespace local_moodle_survey\fill_survey\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class survey_learning_form extends \moodleform {

    public function definition() {

        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);
        $surveydata = $this->_customdata['questions'];

        $mform->addElement('hidden', 'id', $this->_customdata['id']);
        $mform->setType('id', PARAM_INT);
        
        foreach($surveydata as $key => $question) {
            $this->get_survey_learning_questions($mform, $question, $key);
            $mform->addRule($question['questionId'], null, 'required', null, 'client');
        }

        $this->get_form_action_button($mform);
    }

    private function get_survey_learning_questions($mform, $question,  $key) {
        if(!$question['question']) {
            return;
        }
        $mform->addElement('html', '<div class="mode-of-learning-question-section">');
        $mform->addElement('html', '<p class="survey-learning-question">' .  $key . ". " . $question['question'] . '</p>');
        $mform->addElement('html', '<div class="survey-learning-question-option d-flex">');

        $radioarray = [];
        foreach ($question['options'] as $index => $option) {
            $radioarray[] = $mform->createElement('radio', $question['questionId'], null, $option['optionText'], $index);
        }

        $mform->addGroup($radioarray, $question['questionId'], '', array(' '), false);
        $mform->setDefault($question['questionId'], '');

        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }


    private function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('saveandsubmit', 'local_moodle_survey'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton);
        $mform->addElement('html', '</div>');
    }


    public function get_updated_survay_data($formdata) {
        $surveydata = $this->_customdata['questions'];
        $question_options = [];
    
        foreach ($surveydata as $question) {
            $question_options[$question['questionId']] = $question['options'];
        }
    
        $choosesoptions = [];
        foreach ($formdata as $key => $value) {
            $question_id = $key;
            if (isset($question_options[$question_id])) {
                $option_index = intval($value);
                $options = $question_options[$question_id];
                $selected_option = isset($options[$option_index]) ? $options[$option_index] : 'Unknown';
                $choosesoptions[$question_id] = $selected_option;
            }
        }
    
        foreach ($surveydata as &$record) {
            if (isset($record['questionId']) && isset($choosesoptions[$record['questionId']])) {
                $record['answer'] = $choosesoptions[$record['questionId']];
            }
        }
        unset($record);
    
        return $surveydata;
    }
}
