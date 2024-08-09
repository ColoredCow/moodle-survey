<?php
    if (!isset($tab)) {
        $tab = 'general';
    }
    $dbhelper = new \local_moodle_survey\model\survey();
    $surveyquestiondbhelper = new \local_moodle_survey\model\survey_question();
?>

<div id="questions" class="<?php echo $tab === 'questions' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/questions_scores_form.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/question.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey_question.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey_question_option.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/survey.php');

        $questioncategories = $dbhelper->get_all_question_categories();
        $surveyquestions = $surveyquestiondbhelper->get_survey_questions_by_survey_id($survey->id);

        $mform1 = new \local_moodle_survey\form\create\questions_scores_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=questions', ['survey' => $survey, 'questioncategories' => $questioncategories, 'surveyquestions' => $surveyquestions['surveyquestions'], 'surveyquestioncategories' => $surveyquestions['surveyquestioncategories']]);

        if ($mform1->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform1->get_data()) {
            foreach ($data->question as $index => $question) {
                $questionrecord = new stdClass();
                $surveyquestionrecord = new stdClass();
                $questiondbhelper = new \local_moodle_survey\model\question();

                $questionrecord->text = $question['text'];
                $questionrecord->type = 'mcq';

                $existingquestion = $questiondbhelper->get_question_by_question_text($question['text']);
                if (isset($existingquestion->id)) {
                    $existingquestionid = $existingquestion->id;
                }
                if(isset($existingquestionid)) {
                    $questionrecord->id = $existingquestionid;
                    $newquestionid = $existingquestionid;
                    $updatedrecord = $questiondbhelper->update_question($questionrecord);
                } else {
                    $newquestionid = $questiondbhelper->create_question($questionrecord);
                }
                
                $surveyquestionrecord->question_id = $newquestionid;
                $surveyquestionrecord->survey_id = $survey->id;
                $surveyquestionrecord->question_type = 'mcq';
                $surveyquestionrecord->question_position = $index + 1;
                $surveyquestionrecord->question_category_id = $question['category_id'];
                $existingrecord = $surveyquestiondbhelper->get_survey_question_by_survey_id_question_id($survey->id, $newquestionid);
                if(isset($existingrecord->id)) {
                    $surveyquestionrecord->id = $existingrecord->id;
                    $newsurveyquestionid = $existingrecord->id;
                    $surveyquestiondbhelper->update_survey_question($surveyquestionrecord);
                } else {
                    $newsurveyquestionid = $surveyquestiondbhelper->create_survey_question($surveyquestionrecord);
                }

                foreach ($question['option'] as $optionindex => $option) {
                    $optionrecord = new stdClass();
                    $surveyquestionoptiondbhelper = new \local_moodle_survey\model\survey_question_option();
                    $optionrecord->survey_question_id = $newsurveyquestionid;
                    $optionrecord->option_text = $option;
                    $optionrecord->score = $question['score'][0];
                    $optionrecord->option_position = $optionindex + 1;

                    $existingsurveyquestionoptionsrecord = $surveyquestionoptiondbhelper->get_survey_question_options_by_survey_question_id($newsurveyquestionid);
                    if(sizeof($existingsurveyquestionoptionsrecord) > 0) {
                        foreach ($existingsurveyquestionoptionsrecord as $record) {
                            if(isset($record->id)) {
                                $optionrecord->id = $record->id;
                                break;
                            }
                        }
                    }
                    if(isset($optionrecord->id)) {
                        $surveyquestionoptiondbhelper->update_survey_question_options($optionrecord);
                    } else {
                        $surveyquestionoptiondbhelper->create_survey_question_options($optionrecord);
                    }
                }
            }

            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id, 'tab' => 'interpretations']));
        }
        $mform1->display();
    ?>
</div>
