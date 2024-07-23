<?php
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="validity">
    <div class="accordion">
        <div id="question-template" class="accordion-item question-item-section">
            <div class="accordion-header general-details-section">
                <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
                <h5>Duration of collecting responses</h5>
            </div>
            <div class="accordion-body question-score-form">
                <?php
                require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/validity_form.php');
                $mform = new \local_moodle_survey\form\create\validity_form();
                $mform->display();
                ?>
            </div>
        </div>
    </div>
    <?php 
        require_once($CFG->dirroot . '/lib/customformslib.php');
        $buttonsform = new \customformlib(true, get_string('submit', 'local_moodle_survey'));
        $buttonsform->display();
    ?>
</div>

