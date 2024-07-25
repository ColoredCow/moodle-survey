<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="questions" class="<?php echo $tab === 'questions' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/questions_scores_form.php');
        $mform1 = new \local_moodle_survey\form\create\questions_scores_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=questions', ['survey' => $survey]);
        if ($mform1->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform1->get_data()) {
            foreach ($data->question as $index => $question) {
                $questionrecord = new stdClass();
                $surveyquestionrecord = new stdClass();
                
                $questionrecord->text = $question->text;
                $questionrecord->type = 'mcq';

                $newquestion = new stdClass();

                $surveyquestionrecord->question_id = $newquestion->id;
                $surveyquestionrecord->survey_id = $survey->id;
                $surveyquestionrecord->question_type = 'mcq';
                $surveyquestionrecord->position = $index + 1;
                $surveyquestionrecord->question_category_id = $question->category_id;

                $newsurveyquestionrecord = new stdClass();

                foreach ($question['option'] as $optionindex => $option) {
                    $optionrecord = new stdClass();
                    $optionrecord->survey_question_id = $newsurveyquestionrecord->id;
                    $optionrecord->option_text = $option;
                    $optionrecord->score = $question['score'][$index];
                    $optionrecord->option_position = $optionindex + 1;
                }
            }

            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $surveyid, 'tab' => 'interpretations']));
        }
        $mform1->display();
    ?>
</div>
