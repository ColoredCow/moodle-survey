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
        $mform = new \local_moodle_survey\form\create\questions_scores_form();
        $mform->display();
    ?>
</div>
