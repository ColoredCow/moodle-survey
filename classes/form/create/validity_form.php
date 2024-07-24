<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class validity_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);
        $mform->addElement('html', '<div class="accordion">');
        $this->get_validity_form($mform);
        $mform->addElement('html', '</div>');
        $this->get_form_action_button($mform);
    }

    private function get_validity_form($mform) {
        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div id="question-template" class="accordion-item question-item-section">');
        $mform->addElement('html', '<div class="accordion-header general-details-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>Duration of collecting responses</h5>');
        $mform->addElement('html', '</div><div class="accordion-body">');
        $this->get_validity_section($mform);
        $mform->addElement('html', '</div></div>');
    }

    private function get_validity_section($mform) {
        $survey_collection_responses_dates = [
            ['id' => 'survey_collection_responses_start_date', 'label' => get_string('surveyvaliditystartdatelabel', 'local_moodle_survey')],
            ['id' => 'survey_collection_responses_end_date', 'label' => get_string('surveyvalidityenddatelabel', 'local_moodle_survey')],
        ];
        $mform->addElement('html', '<div class="survey-collection-responses-date-section">');
            foreach ($survey_collection_responses_dates as $date) {
                    $this->get_date_field($mform, $date['id'], $date['label']);
            }
        $mform->addElement('html', '</div>');
    }

    private function get_date_field($mform, $dateid, $label) {
        $mform->addElement('date', $dateid , $label, 'maxlength="100" size="30" class=""');
        $mform->setType('question', PARAM_NOTAGS);
        $mform->addRule('question', null, 'required', null, 'client');
    }

    public function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('savechanges'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }
}
