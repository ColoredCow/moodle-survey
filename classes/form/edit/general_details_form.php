<?php
namespace local_moodle_survey\form\edit;

defined('MOODLE_INTERNAL') || die();

class general_details_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;
        $survey = $this->_customdata['survey'];

        $attributes = $mform->getAttributes();
        $attributes['class'] = "create-survey-form";
        $mform->setAttributes($attributes);

        // Add form sections using Moodle form API
        $this->add_survey_name_field($mform, $survey);
        $this->add_survey_category_field($mform, $survey);
        $this->add_survey_description_field($mform, $survey);
 
        // Add action buttons
        $submitbutton = $mform->createElement('submit', 'submitbutton1', get_string('submit', 'local_moodle_survey'), ['class' => 'custom-form-action-btn custom-submit-button']);
        $cancelbutton = $mform->createElement('cancel', 'cancelbutton1', get_string('cancel'), ['class' => 'custom-form-action-btn custom-cancel-button']);
        $mform->addElement('html', '<div class="custom-form-action-buttons">');
        $mform->addElement($cancelbutton);
        $mform->addElement($submitbutton); 
        $mform->addElement('html', '</div>');
    }

    private function add_survey_category_field($mform, $survey) {
        $options = [];
        $surveycategories = $this->_customdata['surveycategories'];
        foreach ($surveycategories as $category) {
            $options[$category->id] = $category->label;
        }
        $mform->addElement('select', 'category_id', get_string('surveycategory', 'local_moodle_survey'), $options);
        $mform->setType('category_id', PARAM_INT);
        $mform->setDefault('status', $survey->category_id);
        $mform->addRule('category_id', null, 'required', null, 'client');
    }

    private function add_survey_description_field($mform, $survey) {
        $mform->addElement('textarea', 'description', get_string('surveydescription', 'local_moodle_survey'), 'wrap="virtual" rows="5" cols="100" class=""');
        $mform->setDefault('description', $survey->description);
        $mform->setType('description', PARAM_TEXT);
    }

    private function add_survey_name_field($mform, $survey) {
        $mform->addElement('text', 'name', get_string('surveyname', 'local_moodle_survey'), 'maxlength="100" size="30" class=""');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $survey->name);
        $mform->addRule('name', null, 'required', null, 'client');
    }
}
