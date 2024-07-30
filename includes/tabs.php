<?php
echo $OUTPUT->header();

if (!isset($tab)) {
    $tab = 'general';
}
?>


<div id="tabs">
    <ul>
        <li class="<?php echo $tab === 'general' ? 'active' : '' ?>">
            <?php echo html_writer::link('#general', get_string('generaldetails', 'local_moodle_survey')) ?>
        </li>
        <li class="<?php echo $tab === 'questions' ? 'active' : '' ?>">
            <?php echo html_writer::link('#questions', get_string('questionsscores', 'local_moodle_survey')) ?>
        </li>
        <li class="<?php echo $tab === 'interpretations' ? 'active' : '' ?>">
            <?php echo html_writer::link('#interpretations', get_string('interpretations', 'local_moodle_survey')) ?>
        </li>
        <li class="<?php echo $tab === 'validity' ? 'active' : '' ?>">
            <?php echo html_writer::link('#validity', get_string('validity', 'local_moodle_survey')) ?>
        </li>
        <li class="<?php echo $tab === 'audience' ? 'active' : '' ?>">
            <?php echo html_writer::link('#audience', get_string('audienceaccess', 'local_moodle_survey')) ?>
        </li>
    </ul>
