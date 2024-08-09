<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

class audience_access_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);
        $sections = [
            [
                'label' => get_string('targetaudience', 'local_moodle_survey'),
                'formlabel' => 'targetaudience',
            ],
            [
                'label' => get_string('accesstoresponse', 'local_moodle_survey'),
                'formlabel' => 'accesstoresponse',
            ],
            [
                'label' => get_string('assigntoschool', 'local_moodle_survey'),
                'formlabel' => 'assigntoschool',
            ]
        ];

        foreach($sections as $section) {
            $this->get_audience_access_form($mform, $section);
        }
        $this->get_form_action_button($mform);
    }

    private function get_audience_access_form($mform, $section) {
        $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
        $mform->addElement('html', '<div class="question-item-section">');
        $mform->addElement('html', '<div class="accordion-header general-details-section">');
        $mform->addElement('html', '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">');
        $mform->addElement('html', '<h5>' . $section['label'] . '</h5>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '<div class="accordion-body question-score-form">');
        $mform->addElement('html', $this->render_audience_access_associated_forms($section['formlabel'], $mform));
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }

    private function render_audience_access_associated_forms($sectionlabel, $mform) {
        switch($sectionlabel){
            case 'targetaudience':
                return $this->target_audience_form($mform);
            case 'accesstoresponse':
                return $this->access_to_response_form($mform);
            case 'assigntoschool':
                return $this->assign_to_school_form($mform);
            default:
                return '';
        }
    }

    private function target_audience_form($mform) {
        $this->get_checkbox_input_fields(get_string('targetaudiencevalues', 'local_moodle_survey'), $mform, 'targetaudience');
        $mform->addRule('targetaudience', get_string('required'), 'required', null, 'client');
    }

    private function access_to_response_form($mform) {
        $this->get_checkbox_input_fields(get_string('accesstoresponsevalues', 'local_moodle_survey'), $mform, 'accesstoresponse');
        $mform->addRule('accesstoresponse', get_string('required'), 'required', null, 'client');
    }

    private function assign_to_school_form($mform) {
        $section = '';
        foreach(get_string('assigntoschools', 'local_moodle_survey') as $key => $stateofschool) {
            $section .= $this->get_select_input_fields($stateofschool, $mform, 'assigntoschool');
        }
        $mform->addElement('html', $section);
        $mform->addRule('assigntoschool', get_string('required'), 'required', null, 'client');
    }

    private function get_select_input_fields($label, $mform, $key) {
        $options = [];
        $states = [
            ['id' => 1, 'label' => 'State 1'],
            ['id' => 2, 'label' => 'State 2'],
            ['id' => 3, 'label' => 'State 3'],
        ];
        foreach ($states as $category) {
            $options[$category['id']] = $category['label'];
        }
        $mform->addElement('select', $key, $label, $options);
        $mform->setType($key, PARAM_INT);
    }

    private function get_checkbox_input_fields($sectionsvalues, $mform, $fieldname) {
        $mform->addElement('html', '<div class="audience-access-form">');
        foreach($sectionsvalues as $key => $accesstoresponsevalue) {
            $mform->addElement('checkbox', $fieldname . '[' . $key . ']', $accesstoresponsevalue);
        }
        $mform->setType($fieldname, PARAM_RAW);
        $mform->addElement('html', '</div>');
    }

    private function get_form_action_button($mform) {
        $submitbutton = $mform->createElement('submit', 'submitbutton1', 'Save & Publish', ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }
}
