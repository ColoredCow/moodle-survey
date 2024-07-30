<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="validity" class="<?php echo $tab === 'validity' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/validity_form.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');

        $mform3 = new \local_moodle_survey\form\create\validity_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=validity', ['survey' => $survey]);
        if ($mform3->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform3->get_data()) {
            $record = new stdClass();
            $record->id = $survey->id;
            $record->start_date = date('Y-m-d', $data->survey_collection_responses_start_date);
            $record->end_date = date('Y-m-d', $data->survey_collection_responses_end_date);

            $dbhelper = new \local_moodle_survey\model\survey();
            $dbhelper->update_survey($record);

            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id, 'tab' => 'audience']));
        }
        $mform3->display();
    ?>
</div>
