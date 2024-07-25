<?php
    if (!isset($tab)) {
        $tab = 'general';
    }
    // Define icon URLs
    $iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $plusicon = new moodle_url('/local/moodle_survey/pix/plus-icon.svg');

    // Include the form class
    require_once($CFG->dirroot . '/local/moodle_survey/classes/form/create/audience_access_form.php');

    // Define sections with labels and form labels
    $sections = [
        [
            'label' => get_string('targetaudience', 'local_moodle_survey'),
            'formlabel' => 'targetaudience'
        ],
        [
            'label' => get_string('accesstoresponse', 'local_moodle_survey'),
            'formlabel' => 'accesstoresponse'
        ],
        [
            'label' => get_string('assigntoschool', 'local_moodle_survey'),
            'formlabel' => 'assigntoschool'
        ]
    ];
?>

<div id="audience" class="<?php echo $tab === 'audience' ? 'active' : '' ?>">
    <?php foreach ($sections as $section): ?>
        <div class="question-item-section">
            <div class="accordion-header general-details-section">
                <?php echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">'; ?>
                <h5><?php echo $section['label']; ?></h5>
            </div>
            <div class="accordion-body question-score-form">
                <?php
                    $mform = new \local_moodle_survey\form\edit\audience_access_form($section['formlabel']);
                    echo $mform->display();
                ?>
            </div>
        </div>
    <?php endforeach; ?>

    <?php 
        require_once($CFG->dirroot . '/local/moodle_survey/lib/customformslib.php');
        $buttonsform = new \customformlib(true, get_string('publishsurveybtn', 'local_moodle_survey'));
        echo $buttonsform->display();
    ?>
</div>
