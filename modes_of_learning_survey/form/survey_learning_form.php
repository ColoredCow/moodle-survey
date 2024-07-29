<?php
namespace local_moodle_survey\modes_of_learning_survey\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class survey_learning_form extends \moodleform {
    private $questions = [
        [
            'question_id' => '1',
            'question_text' => 'How often do you use online learning platforms (e.g., Coursera, Udemy) for your education?',
            'question_options' => ['Never', 'Rarely']
        ],
        [
            'question_id' => '2',
            'question_text' => 'How effective do you find traditional classroom learning compared to online learning?',
            'question_options' => ['Not effective at all', 'Slightly effective', 'Moderately effective', 'Very effective']
        ],
        [
            'question_id' => '3',
            'question_text' => 'How frequently do you engage in self-paced learning (learning at your own speed without a set schedule)?',
            'question_options' => ['Never', 'Rarely', 'Sometimes', 'Often', 'Always']
        ]
    ];

    public function definition() {

        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);

        foreach($this->questions as $key => $question) {
            $this->get_survey_learning_questions($mform, $question, $key);
        }

        $this->get_form_action_button($mform);
    }

    private function get_survey_learning_questions($mform, $question) {
        $mform->addElement('html', '<div class="mode-of-learning-question-section">');
        $mform->addElement('html', '<p class="survey-learning-question">' . $question['question_text'] . '</p>');
        $mform->addElement('html', '<div class="survey-learning-question-option d-flex">');

        $radioarray = [];
        foreach ($question['question_options'] as $index => $option) {
            $radioarray[] = $mform->createElement('radio', $question['question_id'], null, $option, $index);
        }

        $mform->addGroup($radioarray, $question['question_id'], '', array(' '), false);
        // $mform->addRule($question['question_id'], null, 'required', null, 'client');

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
        $options = [];
        foreach ($this->questions as $question) {
            $options[$question['question_id']] = $question['question_options'];
        }
        return $options;
    }
}
