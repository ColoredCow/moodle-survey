<?php
if (!isset($tab)) {
    $tab = 'general';
}
if (!isset($pagetype)) {
    $pagetype = 'create';
}
?>

<div id="general" class="<?php echo $tab === 'general' ? 'active' : '' ?>">
    <div class="accordion">
        <div class="accordion-header general-details-section">
            <?php $iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
                echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">';
            ?>
            <h5><?php echo get_string('surveydetails', 'local_moodle_survey'); ?></h5>
        </div>
        <div class="accordion-body general-details">
            <?php
            require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/general_details_form.php');
            require_once($CFG->dirroot . '/local/moodle_survey/classes/form/edit/general_details_form.php');
            require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');
            $dbhelper = new \local_moodle_survey\model\survey();

            $surveycategories = $dbhelper->get_all_survey_categories();
            $mform = new \local_moodle_survey\form\create\general_details_form(null, ['surveycategories' => $surveycategories]);
            
            if ($pagetype === 'edit') {
                $mform = new \local_moodle_survey\form\edit\general_details_form(null, ['survey' => $survey, 'surveycategories' => $surveycategories]);
            }


            if ($mform->is_cancelled()) {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            } else if ($data = $mform->get_data()) {
                $record = new stdClass();
                $record->name = $data->name;
                $record->description = $data->description;
                $record->category_id = $data->category_id;
                $record->status = 'draft';

                if ($pagetype === 'create') {
                    $surveyid = $dbhelper->create_survey($record);
                } else {
                    $surveyid = $survey->id;
                    $record->id = $surveyid;
                    $dbhelper->update_survey($record);
                }

                redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $surveyid, 'tab' => 'questions']));
            }
            $mform->display();
            ?>
        </div>
    </div>
</div>
