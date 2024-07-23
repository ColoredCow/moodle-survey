<?php
echo $OUTPUT->header();
?>

<div id="tabs">
    <ul>
        <li><?php echo html_writer::link('#general', get_string('generaldetails', 'local_moodle_survey')); ?></li>
        <li><?php echo html_writer::link('#questions', get_string('questionsscores', 'local_moodle_survey')); ?></li>
        <li><?php echo html_writer::link('#interpretations', get_string('interpretations', 'local_moodle_survey')); ?></li>
        <li><?php echo html_writer::link('#validity', get_string('validity', 'local_moodle_survey')); ?></li>
        <li><?php echo html_writer::link('#audience', get_string('audienceaccess', 'local_moodle_survey')); ?></li>
    </ul>
