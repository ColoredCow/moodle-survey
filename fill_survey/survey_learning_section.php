<?php

require_once('../../../config.php');
require_login();
$id = required_param('id', PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
$PAGE->set_heading($survey->name);
$PAGE->set_title($survey->name);
echo $OUTPUT->header();
?>

<div>
    <?php
        $surveydata = $dbhelper->get_survey_data($id);
        require_once($CFG->dirroot . '/local/moodle_survey/fill_survey/form/survey_learning_form.php');
        $mform = new \local_moodle_survey\fill_survey\form\survey_learning_form(null, ['questions' => $surveydata, 'id' => $id]);
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($formdata = $mform->get_data()) {
            $questionoptions = $mform->get_updated_survay_data($formdata);
            $surveyresponsedbhelper = new \local_moodle_survey\model\survey_responses();
            $questionoptionsjson = json_encode($questionoptions);
            $record = new stdClass();
            $record->survey_id = $id;
            $record->status = get_string('completed', 'local_moodle_survey');
            $record->response = $questionoptionsjson;
            $record->submitted_by = $USER->id;
            $response = $surveyresponsedbhelper->create_survey_responses($record);
            $redirecturl = new moodle_url('/local/moodle_survey/fill_survey/survey-insights.php', ['id' => $survey->id]);
            redirect($redirecturl);
        }
        $mform->display();
    ?>
</div>

<?php
echo $OUTPUT->footer();
?>