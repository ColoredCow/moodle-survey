<?php
    if (!isset($tab)) {
        $tab = 'general';
    }
?>

<div id="questions" class="<?php echo $tab === 'questions' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/question.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey_question.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey_question_option.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/questions_scores_form.php');

        function attachQuestionToSurvey($question, $index, $survey) {
            $surveyquestiondbhelper = new \local_moodle_survey\model\survey_question();
            $surveyquestionrecord = new stdClass();
            $surveyquestionrecord->question_id = $question->id;
            $surveyquestionrecord->survey_id = $survey->id;
            $surveyquestionrecord->question_type = 'mcq';
            $surveyquestionrecord->question_position = $index + 1;
            $surveyquestionrecord->question_category_id = $question->category_id;
            $existingrecord = $surveyquestiondbhelper->get_survey_question_by_survey_id_question_id($survey->id, $question->id);

            if(isset($existingrecord->id)) {
                $surveyquestionrecord->id = $existingrecord->id;
                $surveyquestiondbhelper->update_survey_question($surveyquestionrecord);
            } else {
                $surveyquestionrecord->id = $surveyquestiondbhelper->create_survey_question($surveyquestionrecord);
            }

            return $surveyquestionrecord;
        }
        
        function updateOrCreateQuestion($question) {
            $questiondbhelper = new \local_moodle_survey\model\question();
            $questionrecord = new stdClass();
            $questionrecord->text = $question['text'];
            $questionrecord->type = 'mcq';
            $questionrecord->category_id = $question['category_id'];

            if (isset($question['id'])) {
                $questionrecord->id = $question['id'];
                $questiondbhelper->update_question($questionrecord);
            } else {
                $questionrecord->id = $questiondbhelper->create_question($questionrecord);
            }

            return $questionrecord;
        }

        if (count($_POST) && $_POST['tab'] == 'questions') {
            if ($_POST['pressed_button'] == 'cancel') {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }
            $surveyquestionoptiondbhelper = new \local_moodle_survey\model\survey_question_option();
            $questiondbhelper = new \local_moodle_survey\model\question();

            $questions = $_POST['question'];
            $questionidslist = $questiondbhelper->get_questions_ids_for_survey($survey->id);
            $optionidslist = $surveyquestionoptiondbhelper->get_option_ids_for_survey($survey->id);

            foreach ($questions as $index => $question) {
                if (isset($question['id'])) {
                    $indextoremove = array_search($question['id'], $questionidslist);
                    if ($indextoremove !== false) {
                        unset($questionidslist[$indextoremove]);
                    }
                    $questionidslist = array_values($questionidslist);
                }

                $questionrecord = updateOrCreateQuestion($question);
                $surveyquestionrecord = attachQuestionToSurvey($questionrecord, $index, $survey);
                foreach ($question['options'] as $optionindex => $option) {
                    $optionrecord = new stdClass();
                    
                    $optionrecord->survey_question_id = $surveyquestionrecord->id;
                    $optionrecord->option_text = $option['option'];
                    $optionrecord->score = $option['score'];
                    $optionrecord->option_position = $optionindex + 1;

                    if(isset($option['id'])) {
                        $indextoremove = array_search($option['id'], $optionidslist);
                        if ($indextoremove !== false) {
                            unset($optionidslist[$indextoremove]);
                        }
                        $optionidslist = array_values($optionidslist);
                        
                        $optionrecord->id = $option['id'];
                        $surveyquestionoptiondbhelper->update_survey_question_options($optionrecord);
                    } else {
                        $surveyquestionoptiondbhelper->create_survey_question_options($optionrecord);
                    }
                }
            }            
            $surveyquestionoptiondbhelper->delete_list_of_question_options($optionidslist);
            $questiondbhelper->delete_list_of_questions($questionidslist);
            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id, 'tab' => 'interpretations']));
        }
    ?>
</div>
