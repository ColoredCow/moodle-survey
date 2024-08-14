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

        function get_updated_survey_data($surveydata, $questions) {
            $surveyquestionoptiondbhelper = new \local_moodle_survey\model\survey_question_option();
        
            if (!isset($surveydata['surveyData']['categoriesScores'])) {
                $surveydata['surveyData']['categoriesScores'] = [];
            }
        
            foreach ($questions as $key => $data) {
                $option = $surveyquestionoptiondbhelper->get_options_by_option_text($data);
                $categorySlug = $surveydata[$key]['questionCategory'];
                $score = $option->score;
        
                // Flag to check if category was updated
                $categoryUpdated = false;
        
                // Iterate through existing categoriesScores to update or add new category
                foreach ($surveydata['surveyData']['categoriesScores'] as &$category) {
                    if (isset($category[$categorySlug])) {
                        $category[$categorySlug]['score'] = $score;
                        $categoryUpdated = true;
                        break;
                    }
                }
        
                // If the category was not found, add it
                if (!$categoryUpdated) {
                    $surveydata['surveyData']['categoriesScores'][] = [
                        [
                            "catgororySlug" => $categorySlug,
                            "score" => $score,
                        ]
                    ];
                }
        
                // Set answer and score for the current question
                $surveydata[$key]['answer'] = $data;
                $surveydata[$key]['score'] = $score;
            }
        
            return $surveydata;
        }        


        if (count($_POST)) {
            if ($_POST['pressed_button'] == 'cancel') {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }

            $questions = $_POST['question'];
            $updatedsurveydata = get_updated_survey_data($surveydata, $questions);
            $surveyresponsedbhelper = new \local_moodle_survey\model\survey_responses();
            $questionoptionsjson = json_encode($updatedsurveydata);
            $record = new stdClass();
            $record->survey_id = $id;
            $record->status = get_string('completed', 'local_moodle_survey');
            $record->response = $questionoptionsjson;
            $record->submitted_by = $USER->id;
            $response = $surveyresponsedbhelper->create_survey_responses($record);
            $redirecturl = new moodle_url('/local/moodle_survey/fill_survey/survey_insights.php', ['id' => $survey->id]);
            redirect($redirecturl);
        }
    ?>
</div>

<?php
echo $OUTPUT->footer();
?>