<?php
if (!isset($tab)) {
    $tab = 'general';
}
?>
<div id="interpretations" class="<?php echo $tab === 'interpretations' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/interpretations_form.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/question_category_interpretation.php');
        
        $dbhelper = new \local_moodle_survey\model\survey();
        $questioncategories = $dbhelper->get_question_categories_for_survey($survey->id);

        $mform2 = new \local_moodle_survey\form\create\interpretations_form('/local/moodle_survey/edit_survey.php?id=' . $survey->id . '&tab=interpretations', ['survey' => $survey, 'questioncategories' => $questioncategories]);

        if ($mform2->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform2->get_data()) {
            $dbhelper = new \local_moodle_survey\model\question_category_interpretation();
            foreach ($data->interpretation as $index => $interpretation) {
                $interpretationrecord = new stdClass();
                $interpretationrecord->survey_id = $survey->id;
                $interpretationrecord->question_category_id = $interpretation;
                $interpretationrecord->interpreted_as = $data->interpretedas[$index];
                $interpretationrecord->score_from = $data->scorefrom[$index];
                $interpretationrecord->score_to = $data->scoreto[$index];
                $interpretationrecord->description = null;
                $existingrecord = $dbhelper->get_interpretation_by_survey_id($survey->id, $interpretation);
                if(sizeof($existingrecord) > 0) {
                    foreach($existingrecord as $record) {
                        $existingrecordid = $record->id;
                        break;
                    }
                }
                if(isset($existingrecordid)) {
                    $interpretationrecord->id = $existingrecordid;
                    $dbhelper->update_survey($interpretationrecord);
                } else {
                    $dbhelper->create_interpretation($interpretationrecord);
                }
            }
            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id, 'tab' => 'validity']));
        }

        $mform2->display();
        ?>
</div>
