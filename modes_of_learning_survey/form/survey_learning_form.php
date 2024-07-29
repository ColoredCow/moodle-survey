<?php
namespace local_moodle_survey\modes_of_learning_survey\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class survey_learning_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);
        $this->get_survey_learning_questions($mform);
        $this->get_form_action_button($mform);
    }

    private function get_survey_learning_questions($mform) {
        $mform->addElement('html', '<div class="mode-of-learning-question-section">');
        $mform->addElement('html', '<p class="survey-learning-question">How often do you use online learning platforms (e.g., Coursera, Udemy) for your education?</p>');
        $mform->addElement('html', '<div class="survey-learning-question-option d-flex">');
        $mform->addElement('radio', 'name_of_element', null, 'Never');
        $mform->addElement('radio', 'name_of_element', null, 'Rarely');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }

    private function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('savechanges'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }
}
