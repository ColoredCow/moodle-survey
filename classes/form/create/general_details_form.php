<?php
namespace local_moodle_survey\form\create;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/customformslib.php");

class general_details_form extends \customformlib {
    public function definition() {
        $mform = $this->_form;

        // Add custom HTML for form heading
        $mform->addElement('html', '<div class="form-section">');
        $this->add_survey_category_field($mform);
        $this->add_survey_description_field($mform);

        // Add action buttons
        $this->add_custom_action_buttons_helper(true, get_string('submit', 'local_moodle_survey'));
    }


    private function add_survey_category_field($mform) {
        $iconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');

        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="id_name">' . get_string('surveycategory', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<select id="id_name" name="status" class="form-control" required>');
        $mform->addElement('html', '<option value="0">' . get_string('inactive', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '<option value="1">' . get_string('active', 'local_moodle_survey') . '</option>');
        $mform->addElement('html', '</select>');
        $mform->addElement('html', '<div class="new-option-section">');
        $mform->addElement('html', '<div id="new-category-input-container"></div>');
        $mform->addElement('html', '<button type="button" id="add-category-button" class="add-new-button"><img src="' . $iconurl . '" alt="Icon" class="plus-icon">' . get_string('newsurveycategory', 'local_moodle_survey') . '</button>');
        $mform->addElement('html', '</div> </div>');
    }

    private function add_survey_description_field($mform) {
        $mform->addElement('html', '<div class="form-group">');
        $mform->addElement('html', '<label for="id_description">' . get_string('surveydescription', 'local_moodle_survey') . '</label>');
        $mform->addElement('html', '<textarea id="id_description" placeholder="' . get_string('surveydescriptionplaceholder', 'local_moodle_survey') . '" name="description" wrap="virtual" rows="5" cols="100" class="form-control"></textarea>');
        $mform->addElement('html', '</div>');
        $mform->addElement('html', '</div>');
    }
}
