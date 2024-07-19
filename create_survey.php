<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
// require_capability('local/moodle_survey:create', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/create_survey.php'));
$PAGE->set_title(get_string('createsurvey', 'local_moodle_survey'));
$PAGE->set_heading(get_string('createsurvey', 'local_moodle_survey'));

$PAGE->requires->css(new moodle_url('/local/moodle_survey/styles.css'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/tabs.js'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/forms.js'));

// $mform = new \local_moodle_survey\form\create_survey_form();

echo $OUTPUT->header();

// Tab navigation
echo html_writer::start_tag('div', ['id' => 'tabs']);
echo html_writer::start_tag('ul');
echo html_writer::tag('li', html_writer::link('#general', get_string('generaldetails', 'local_moodle_survey')));
echo html_writer::tag('li', html_writer::link('#questions', get_string('questionsscores', 'local_moodle_survey')));
echo html_writer::tag('li', html_writer::link('#interpretations', get_string('interpretations', 'local_moodle_survey')));
echo html_writer::tag('li', html_writer::link('#validity', get_string('validity', 'local_moodle_survey')));
echo html_writer::tag('li', html_writer::link('#audience', get_string('audienceaccess', 'local_moodle_survey')));
echo html_writer::end_tag('ul');

// General Details form
echo html_writer::start_tag('div', ['id' => 'general']);
$mform = new \local_moodle_survey\form\create\general_details_form();
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
} else if ($data = $mform->get_data()) {
    // Insert the survey into the database.
    $record = new stdClass();
    $record->name = $data->name;
    $record->description = $data->description;
    $record->status = $data->status;
    $DB->insert_record('moodle_survey', $record);

    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
}
$mform->display();
echo html_writer::end_tag('div');

// Questions & Scores form
echo html_writer::start_tag('div', ['id' => 'questions']);
$mform = new \local_moodle_survey\form\create\questions_scores_form();
$mform->display();
echo html_writer::end_tag('div');

// Interpretations form
echo html_writer::start_tag('div', ['id' => 'interpretations']);
$mform = new \local_moodle_survey\form\create\interpretations_form();
$mform->display();
echo html_writer::end_tag('div');

// Validity form
echo html_writer::start_tag('div', ['id' => 'validity']);
$mform = new \local_moodle_survey\form\create\validity_form();
$mform->display();
echo html_writer::end_tag('div');

// Audience & Access form
echo html_writer::start_tag('div', ['id' => 'audience']);
$mform = new \local_moodle_survey\form\create\audience_access_form();
$mform->display();
echo html_writer::end_tag('div');

echo html_writer::end_tag('div');

echo $OUTPUT->footer();
