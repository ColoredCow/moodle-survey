<?php
if (!isset($tab)) {
    $tab = 'general';
}
?>
<div id="interpretations" class="<?php echo $tab === 'interpretations' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/model/question_category_interpretation.php');
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/interpretations_form.php');
        
        if (count($_POST) && $_POST['tab'] == 'interpretations') {
            if ($_POST['pressed_button'] == 'cancel') {
                redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
            }

            $dbhelper = new \local_moodle_survey\model\question_category_interpretation();
            $interpretationidsList = $dbhelper->get_interpretation_ids_by_survey_id($survey->id);

            foreach ($_POST['interpretation'] as $index => $questioncategory) {
                foreach ($questioncategory['interpretations'] as $interpretation) {
                    $interpretationrecord = new stdClass();
                    $interpretationrecord->survey_id = $survey->id;
                    $interpretationrecord->question_category_id = $questioncategory['category_id'];
                    $interpretationrecord->interpreted_as = $interpretation['interpreted_as'];
                    $interpretationrecord->score_from = $interpretation['from'];
                    $interpretationrecord->score_to = $interpretation['to'];
                    $interpretationrecord->description = $interpretation['interpreted_as_description'];

                    if (isset($interpretation['id'])) {
                        $indextoremove = array_search($interpretation['id'], $interpretationidsList);
                        if ($indextoremove !== false) {
                            unset($interpretationidsList[$indextoremove]);
                        }
                        $interpretationidsList = array_values($interpretationidsList);

                        $interpretationrecord->id = $interpretation['id'];
                        $dbhelper->update_interpretation($interpretationrecord);
                    } else {
                        $dbhelper->create_interpretation($interpretationrecord);
                    }
                }
            }

            $dbhelper->delete_list_of_interpretations($interpretationidsList);

            redirect(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id, 'tab' => 'validity']));
        }
    ?>
</div>
