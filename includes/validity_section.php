<?php
if (!isset($tab)) {
    $tab = 'general';
}
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="validity" class="<?php echo $tab === 'validity' ? 'active' : '' ?>">
    <?php
        require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/validity_form.php');
        $mform = new \local_moodle_survey\form\create\validity_form();
        $mform->display();
    ?>
</div>
