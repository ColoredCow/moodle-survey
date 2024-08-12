<?php

require_once('../../../config.php');
require_login();
$id = required_param('id', PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
$PAGE->set_heading($survey->name);
$PAGE->set_title($survey->name);
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js')); // Need to move this line inside the config file in moodle_survey plugin
$surveydata = $dbhelper->get_survey_data($id);
echo $OUTPUT->header();
?>

<div>
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/fill_survey/form/survey_learning_form.php');

        function get_updated_survay_data($surveydata, $questions) {
            $question_options = [];
        
            foreach ($surveydata as $question) {
                $question_options[$question['questionId']] = $question['options'];
            }
        
            $choosesoptions = [];
            foreach ($questions as $key => $value) {
                $question_id = $key;
                if (isset($question_options[$question_id])) {
                    $option_index = intval($value);
                    $options = $question_options[$question_id];
                    $selected_option = isset($options[$option_index]) ? $options[$option_index] : 'Unknown';
                    $choosesoptions[$question_id] = $selected_option;
                }
            }
        
            foreach ($surveydata as &$record) {
                if (isset($record['questionId']) && isset($choosesoptions[$record['questionId']])) {
                    $record['answer'] = $choosesoptions[$record['questionId']];
                }
            }
            unset($record);
        
            return $surveydata;
        }


        if (count($_POST)) {
            if ($_POST['pressed_button'] == 'cancel') {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }

            $questions = $_POST['question'];
            $updatedsurveydata = get_updated_survay_data($surveydata, $questions);
            $surveyresponsedbhelper = new \local_moodle_survey\model\survey_responses();
            $questionoptionsjson = json_encode($updatedsurveydata);
            $record = new stdClass();
            $record->survey_id = $id;
            $record->status = get_string('completed', 'local_moodle_survey');
            $record->response = $questionoptionsjson;
            $record->submitted_by = $USER->id;
            $response = $surveyresponsedbhelper->create_survey_responses($record);
            $redirecturl = new moodle_url('/local/moodle_survey/fill_survey/survey-insights.php', ['id' => $survey->id]);
            redirect($redirecturl);
        }
    ?>
</div>

<?php
echo $OUTPUT->footer();
?>