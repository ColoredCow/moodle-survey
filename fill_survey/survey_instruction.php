<?php
    echo html_writer::start_tag('div', array('class' => 'survey-submission-container'));

        echo html_writer::tag('p', get_string('suveyparticipationcontent', 'local_moodle_survey'), array('class' => 'survey-thanks-content'));

        echo html_writer::start_tag('div', array('class' => 'survey-about-container'));
            echo html_writer::tag('h3', get_string('whatsurveyabout', 'local_moodle_survey'), array('class' => 'survey-about-heading'));
            echo html_writer::tag('p', $survey->description);
        echo html_writer::end_tag('div');
        echo html_writer::start_tag('div', array('class' => 'survey-instruction-container'));
            echo html_writer::tag('h3', get_string('instructionforfiillingsurveyheading', 'local_moodle_survey'), array('class' => 'survey-instruction-heading'));
            echo html_writer::start_tag('ol');
            foreach(get_string('instructionforfiillingsurvey', 'local_moodle_survey') as $value) {
                echo html_writer::tag('li', $value);
            }
            echo html_writer::end_tag('ol');
        echo html_writer::end_tag('div');

        echo html_writer::start_tag('div', array('class' => 'survey-participation-confirmation'));
            echo html_writer::checkbox('survey-participation', $surveyparticipation, null, get_string('sruveyacceptancetext', 'local_moodle_survey'), array('id' => 'survey-participation-checkbox'));
        echo html_writer::end_tag('div');

    echo html_writer::end_tag('div');
?>

<!-- Action Buttons -->
<?php
$submitlabel = get_string('submit', 'local_moodle_survey');
$cancellabel = get_string('cancel');
$continueurl = new moodle_url('/local/moodle_survey/fill_survey/fill_survey_section.php', ['id' => $survey->id]);
$cancelurl = new moodle_url('/local/moodle_survey/manage_survey.php');

echo html_writer::div(
    html_writer::link(
        $cancelurl,
        $cancellabel,
        array('class' => 'custom-declined-button custom-action-btn')
    ) . html_writer::link(
        $continueurl,
        $submitlabel,
        array('id' => 'continue-button', 'class' => 'custom-continue-button custom-action-btn', 'role' => 'button', 'aria-disabled' => 'true', 'data-disabled' => 'true')
    ),
    'custom-form-action-buttons'
);
?>

