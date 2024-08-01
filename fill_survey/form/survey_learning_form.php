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
        $questions = $this->_customdata['questions'];

        foreach($questions as $key => $question) {
            $this->get_survey_learning_questions($mform, $question, $key);
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
            $radioarray[] = $mform->createElement('radio', $question['questionId'], null, $option, $index);
        }

        $mform->addGroup($radioarray, $question['questionId'], '', array(' '), false);
        // $mform->addRule($question['questionId'], null, 'required', null, 'client');
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


    public function get_question_options() {
        $questions = $this->_customdata['questions'];
        $options = [];
        foreach ($questions as $question) {
            $options[$question['questionId']] = $question['options'];
        }
        return $options;
    }
}
