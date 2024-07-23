<?php
    $iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $plusicon = new moodle_url('/local/moodle_survey/pix/plus-icon.svg');
    require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/audience_access_form.php');
    $mform = new \local_moodle_survey\form\create\audience_access_form();
?>

<div id="audience">
    <div class="question-item-section">
        <div class="accordion-header general-details-section">
            <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
            <h5><?php echo get_string('targetaudience', 'local_moodle_survey') ?></h5>
        </div>
        <div class="accordion-body question-score-form">
            <?php echo $mform->targetAudienceForm() ?>
        </div>
    </div>
    
    <div class="question-item-section">
        <div class="accordion-header general-details-section">
            <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
            <h5><?php echo get_string('accesstoresponse', 'local_moodle_survey') ?></h5>
        </div>
        <div class="accordion-body question-score-form">
            <?php echo $mform->accessToResponseForm() ?>
        </div>
    </div>
    
    <div class="question-item-section">
        <div class="accordion-header general-details-section">
            <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
            <h5><?php echo get_string('assigntoschool', 'local_moodle_survey') ?></h5>
        </div>
        <div class="accordion-body question-score-form">
            <?php echo $mform->assignToSchoolForm() ?>
        </div>
    </div>

    <?php 
        require_once($CFG->dirroot . '/lib/customformslib.php');
        $buttonsform = new \customformlib(true, get_string('publishsurveybtn', 'local_moodle_survey'));
        $buttonsform->display();
    ?>
</div>