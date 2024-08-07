<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="audience" class="<?php echo $tab === 'audience' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/audience_access_form.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');

        $mform4 = new \local_moodle_survey\form\create\audience_access_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=audience', ['survey' => $survey]);
        if ($mform4->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform4->get_data()) {
            $surveyrecord = new stdClass();
            $surveyrecord->id = $survey->id;
            $surveyrecord->status = get_string('published', 'local_moodle_survey');
            
            $targetaudience = isset($data->targetaudience) ? array_filter($data->targetaudience) : [];
            $surveyrecord->targetaudience = json_encode($targetaudience);
            
            $accesstoresponse = isset($data->accesstoresponse) ? array_filter($data->accesstoresponse) : [];
            $surveyrecord->accesstoresponse = json_encode($accesstoresponse);
            
            $assigntoschool = isset($data->assigntoschool) ? implode(',', (array) $data->assigntoschool) : '';
            $surveyrecord->assigntoschool = $assigntoschool;
            var_dump($surveyrecord->targetaudience);
            
            // $dbhelper = new \local_moodle_survey\model\survey();
            // $dbhelper->update_survey($surveyrecord);

            // redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        }
        $mform4->display();
    ?>
</div>
