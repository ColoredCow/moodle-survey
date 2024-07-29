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
        $mform->display();
        ?>
</div>

<?php
echo $OUTPUT->footer();
?>

