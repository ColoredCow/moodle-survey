<?php

require_once('../../../config.php');
require_login();

$PAGE->set_heading('Modes of Learning');
echo $OUTPUT->header();
?>

<div>
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/modes_of_learning_survey/form/survey_learning_form.php');
        $mform = new \local_moodle_survey\modes_of_learning_survey\form\survey_learning_form();
        if ($mform->is_cancelled()) {
            redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
        } else if ($data = $mform->get_data()) {
            $question_options = $mform->get_question_options();
            $results = [];
            foreach ($data as $key => $value) {
                $question_id = $key;
                if (isset($question_options[$question_id])) {
                    $option_index = intval($value);
                    $options = $question_options[$question_id];
                    $selected_option = isset($options[$option_index]) ? $options[$option_index] : 'Unknown';
                    $results[$question_id] = $selected_option;
                }
            }
            redirect(new moodle_url('/local/moodle_survey/modes_of_learning_survey/learning-survey-insights.php'));
        }
        $mform->display();
    ?>
</div>

<?php
echo $OUTPUT->footer();
?>

