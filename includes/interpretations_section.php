<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="interpretations" class="<?php echo $tab === 'interpretations' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/interpretations_form.php');
        $mform = new \local_moodle_survey\form\create\interpretations_form();
        $mform->display();
        ?>
</div>
