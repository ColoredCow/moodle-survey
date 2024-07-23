<?php
$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
$plusicon = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
?>
<div id="questions">
    <div class="accordion">
        <div id="question-template" class="accordion-item question-item-section">
            <div class="accordion-header general-details-section">
                <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
                <h5>Question <span class="question-number">1</span></h5>
            </div>
            <div class="accordion-body question-score-form">
                <?php
                require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/questions_scores_form.php');
                $mform = new \local_moodle_survey\form\create\questions_scores_form();
                $mform->display();
                ?>
            </div>
        </div>
        <button type="button" id="add-new-question-button" class="add-new-button">
            <img src="<?php echo $plusicon; ?>" alt="Icon" class="plus-icon"><?php echo get_string('addnewquestion', 'local_moodle_survey'); ?>
        </button>
    </div>
    <?php 
        require_once($CFG->dirroot . '/lib/customformslib.php');
        $buttonsform = new \customformlib(true, get_string('submit', 'local_moodle_survey'));
        $buttonsform->display();
    ?>
</div>
