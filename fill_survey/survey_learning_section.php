<?php

require_once('../../../config.php');
require_login();
$id = optional_param('id', 1, PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
// // Get question category interpretations
// $questioncategoryinterpretations = $DB->get_records('cc_question_category_interpretations', ['survey_id' => $id], '*', MUST_EXIST);

$PAGE->set_heading($survey->name);
echo $OUTPUT->header();
?>

<div>
    <?php
        $surveydata = $dbhelper->get_survey_data($id);
        require_once($CFG->dirroot . '/local/moodle_survey/fill_survey/form/survey_learning_form.php');
        $mform = new \local_moodle_survey\fill_survey\form\survey_learning_form(null, ['questions' => $surveydata]);
        
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($formdata = $mform->get_data()) {
            $questionoptions = $mform->get_updated_survay_data($formdata);
            $questionoptionsjson = json_encode($questionoptions);
            $redirecturl = new moodle_url('/local/moodle_survey/fill_survey/learning-survey-insights.php');
            redirect($redirecturl);
        }
            
        //     // // Serialize and encode results for safe URL usage
        //     // $redirecturl = new moodle_url('/local/moodle_survey/fill_survey/learning-survey-insights.php', ['id' => $id]);
        //     // echo '<pre>Redirecting to: ' . htmlspecialchars($redirecturl->out()) . '</pre>';

        //     // redirect($redirecturl);
        // }
        $mform->display();
    ?>
</div>

<?php
echo $OUTPUT->footer();
?>