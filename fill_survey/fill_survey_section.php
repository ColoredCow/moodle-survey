<?php

require_once('../../../config.php');
require_login();
$id = required_param('id', PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$questioncategoryinterpretationdbhelper = new \local_moodle_survey\model\question_category_interpretation();
$survey = $dbhelper->get_survey_by_id($id);
$PAGE->set_heading($survey->name);
$PAGE->set_title($survey->name);
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
$surveydata = $dbhelper->get_survey_data($id);
$interpretationdata = $questioncategoryinterpretationdbhelper->get_interpretation_by_survey_id($id);
echo $OUTPUT->header();
?>

<div>
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/fill_survey/form/survey_learning_form.php');
        function get_updated_survey_data($surveydata, $questions, $interpretationdata) {

            $surveyquestionoptiondbhelper = new \local_moodle_survey\model\survey_question_option();
        
            if (!isset($surveydata['surveyData']['categoriesScores'])) {
                $surveydata['surveyData']['categoriesScores'] = [];
            }
        
            $categoryScoresMap = [];
            $surveyquestions = [];

            foreach ($surveydata as $key => $data) {
                if($data['question']) {
                    $surveyquestions[] = $data;
                }
            }
            
            
            foreach ($questions as $key => $data) {
                foreach($surveyquestions as $surveyquestion) {
                    if($surveyquestion['questionId'] == $key) {
                        foreach($surveyquestion['options'] as $option) {
                            if($option['optionText'] == $data) {
                                $optionscore = $option['score'];
                            }
                        }
                    }
                }
                $categorySlug = $surveydata[$key]['questionCategory'];
                $score = $optionscore;
        
                // Accumulate scores by category
                if (isset($categoryScoresMap[$categorySlug])) {
                    $categoryScoresMap[$categorySlug] += $score;
                } else {
                    $categoryScoresMap[$categorySlug] = $score;
                }
        
                // Set answer and score for the current question
                $surveydata[$key]['answer'] = $data;
                $surveydata[$key]['score'] = $score;
            }
        
            $surveydata['surveyData']['categoriesScores'] = [];
            foreach ($categoryScoresMap as $categorySlug => $score) {
                $surveydata['surveyData']['categoriesScores'][] = [
                    "catgororySlug" => $categorySlug,
                    "score" => (int)$score,
                ];
        
                $existinginterpretationdata = $surveydata['surveyData']['interpretations'];
        
                $interpretationforscore = get_interpretation_for_score($interpretationdata, $score);
                foreach ($existinginterpretationdata as &$interpretation) {
                    if (isset($interpretation[$categorySlug])) {
                        $interpretation[$categorySlug]['description'] = $interpretationforscore['description'];
                        $interpretation[$categorySlug]['text'] = $interpretationforscore['interpreted_as'];
                        $interpretation[$categorySlug]['range'] = $interpretationforscore['score_from'] . ' - ' . $interpretationforscore['score_to'];
                    }
                }
                $surveydata['surveyData']['interpretations'] = $existinginterpretationdata;
            }
        
            unset($interpretation);
            return $surveydata;
        }

        function get_interpretation_for_score($interpretationData, $score) {
            foreach ($interpretationData as $interpretation) {
                if ($score >= $interpretation->score_from && $score <= $interpretation->score_to) {
                    return [
                        'score_from' => $interpretation->score_from,
                        'score_to' => $interpretation->score_to,
                        'interpreted_as' => $interpretation->interpreted_as,
                        'description' => $interpretation->description
                    ];
                }
            }
            return null;
        }

        if (count($_POST)) {
            if ($_POST['pressed_button'] == 'cancel') {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }

            $questions = $_POST['question'];
            $updatedsurveydata = get_updated_survey_data($surveydata, $questions, $interpretationdata);
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