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
        $mform->addElement('html', '</div>'); // Close accordion-header
        $mform->addElement('html', '<div class="accordion-body question-score-form">');
        $mform->addElement('html', $this->render_audience_access_associated_forms($section['formlabel']));
        $mform->addElement('html', '</div>'); // Close accordion-body
        $mform->addElement('html', '</div>'); // Close question-item-section
    }

    private function render_audience_access_associated_forms($sectionlabel) {
        switch($sectionlabel){
            case 'targetaudience':
                return $this->target_audience_form();
            case 'accesstoresponse':
                return $this->access_to_response_form();
            case 'assigntoschool':
                return $this->assign_to_school_form();
            default:
                return ''; // In case of an invalid label
        }
    }

    private function target_audience_form() {
        return $this->get_checkbox_input_fields(get_string('targetaudiencevalues', 'local_moodle_survey'));
    }

    private function access_to_response_form() {
        return $this->get_checkbox_input_fields(get_string('accesstoresponsevalues', 'local_moodle_survey'));
    }

    private function assign_to_school_form() {
        $section = '';
        foreach(get_string('assigntoschools', 'local_moodle_survey') as $key => $state_of_school) {
            $section .= $this->get_select_input_fields($key, $state_of_school);
        }
        return $section;
    }

    private function get_select_input_fields($key, $label) {
        $mform = $this->_form;
        $class = ($key === 'select_school') ? 'form-group select-schools-section' : 'form-group';
        $mform->addElement('html', '<div class="' . $class . '">');
        $mform->addElement('html', '<label for="' . $key . '">' . $label . '</label>');
        $mform->addElement('html', '<select id="' . $key . '" name="' . $key . '" class="form-control" required>');
        $mform->addElement('html', '<option value="0">' . get_string('inactive', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '<option value="1">' . get_string('active', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '</select>');
        $mform->addElement('html', '</div>');
    }

    private function get_checkbox_input_fields($sections_values) {
        $mform = $this->_form;
        $html = '<div class="audience-access-form">';
        foreach($sections_values as $key => $access_to_response_value) {
            $html .= '<div class="checkbox-section">';
            $html .= '<input type="checkbox" id="' . $key . '" name="' . $key . '" class="form-control">';
            $html .= '<label for="' . $key . '" class="checkbox-label">' . $access_to_response_value . '</label>';
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
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
