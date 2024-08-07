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
            $audienceaccessrecord = new stdClass();
            $surveyrecord = new stdClass();
            $dbhelper = new \local_moodle_survey\model\survey();
            $audienceaccess = new \local_moodle_survey\model\audience_access();

            $audienceaccessrecord->survey_id = $survey->id;
            
            $targetaudience = isset($data->targetaudience) ? array_filter($data->targetaudience) : [];
            $audienceaccessrecord->target_audience = json_encode($targetaudience);
            
            $accesstoresponse = isset($data->accesstoresponse) ? array_filter($data->accesstoresponse) : [];
            $audienceaccessrecord->access_to_response = json_encode($accesstoresponse);
            
            $assigntoschool = isset($data->assigntoschool) ? implode(',', (array) $data->assigntoschool) : '';
            $audienceaccessrecord->school_id = $assigntoschool;

            $existingaudienceaccess = $audienceaccess->get_audience_acccess_by_survey_id($survey->id);

            $survey = $dbhelper->get_survey_by_id($survey->id);
            if(isset($existingaudienceaccess->id)) {
                $audienceaccessrecord->id = $existingaudienceaccess->id;
                $audienceaccess->update_audience_access($audienceaccessrecord);
            } else {
                $audienceaccess->create_audience_access($audienceaccessrecord);
            }

            if(isset($survey->id)) {
                $surveyrecord->id = $survey->id;
                $surveyrecord ->status = get_string('live', 'local_moodle_survey');
                $dbhelper->update_survey($surveyrecord);
            }

            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        }
        $mform4->display();
    ?>
</div>
