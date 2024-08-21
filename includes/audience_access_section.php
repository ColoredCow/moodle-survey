<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
$audienceaccess = new \local_moodle_survey\model\audience_access();
$surveyschools = $audienceaccess->get_schools();
$existingaudienceaccess = $audienceaccess->get_audience_acccess_by_survey_id($survey->id);
?>
<div id="audience" class="<?php echo $tab === 'audience' ? 'active' : '' ?>">
    <?php
        if($ispagetypecreate) {
            echo html_writer::tag('div', get_string('fillgeneraldetailsform', 'local_moodle_survey'), ['class' => 'alert alert-info']);
        }
        else {
            require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/audience_access_form.php');
            require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');

            $mform4 = new \local_moodle_survey\form\create\audience_access_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=audience', ['survey' => $survey, 'audienceaccess' => $existingaudienceaccess, 'schools' => $surveyschools]);
            if ($mform4->is_cancelled()) {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            } else if ($data = $mform4->get_data()) {
                $audienceaccessrecord = new stdClass();
                $surveyrecord = new stdClass();
                $dbhelper = new \local_moodle_survey\model\survey();

                $audienceaccessrecord->survey_id = $survey->id;
                
                $targetaudience = isset($data->targetaudience) ? array_keys(array_filter($data->targetaudience)) : [];
                $audienceaccessrecord->target_audience = json_encode($targetaudience);
                
                $accesstoresponse = isset($data->accesstoresponse) ? array_keys(array_filter($data->accesstoresponse)) : [];
                $audienceaccessrecord->access_to_response = json_encode($accesstoresponse);
                
                $assigntoschoolids = isset($data->assigntoschool) ? $data->assigntoschool : '';
                
                $survey = $dbhelper->get_survey_by_id($survey->id);
                
                foreach($assigntoschoolids as $schoolid) {
                    $schoolid = (int)$schoolid;
                    $existingrecord = $audienceaccess->get_audience_access_by_school_id_survey_id($survey->id, $schoolid);
                
                    if ($existingrecord) {
                        $audienceaccessrecord->id = $existingrecord->id;
                        $audienceaccessrecord->school_id = $schoolid;
                        $audienceaccess->update_audience_access($audienceaccessrecord);
                    } else {
                        $audienceaccessrecord->school_id = $schoolid;
                        $audienceaccess->create_audience_access($audienceaccessrecord);
                    }
                }

                foreach ($existingaudienceaccess as $existingrecord) {
                    if (!in_array($existingrecord->school_id, $assigntoschoolids)) {
                        $audienceaccess->delete_survey_audience_access($existingrecord->id);
                    }
                }

                if(isset($survey->id)) {
                    $surveyrecord->id = $survey->id;
                    $surveyrecord ->status = get_string('live', 'local_moodle_survey');
                    $dbhelper->update_survey($surveyrecord);
                }

                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }
            $mform4->display();
        }
    ?>
</div>
