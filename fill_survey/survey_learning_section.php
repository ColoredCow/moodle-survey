<?php

require_once('../../../config.php');
require_login();
$id = optional_param('id', null, PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
$surveydata = $dbhelper->get_survey_data($id);
// // Get question category interpretations
// $questioncategoryinterpretations = $DB->get_records('cc_question_category_interpretations', ['survey_id' => $id], '*', MUST_EXIST);

$PAGE->set_heading($survey->name);
echo $OUTPUT->header();
?>

<div>
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/fill_survey/form/survey_learning_form.php');
        $mform = new \local_moodle_survey\fill_survey\form\survey_learning_form(null, ['questions' => $surveydata]);
        
        // if ($mform->is_cancelled()) {
        //     redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        // } else if ($data = $mform->get_data()) {
        //     $question_options = $mform->get_question_options();
        //     $mcqresults = [];
        //     foreach ($data as $key => $value) {
        //         $question_id = $key;
        //         if (isset($question_options[$question_id])) {
        //             $option_index = intval($value);
        //             $options = $question_options[$question_id];
        //             $selected_option = isset($options[$option_index]) ? $options[$option_index] : 'Unknown';
        //             $mcqresults[$question_id] = $selected_option;
        //         }
        //     }
            
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
