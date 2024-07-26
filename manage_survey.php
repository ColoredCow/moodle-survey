<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/manage_survey.php'));
$PAGE->set_title(get_string('managesurvey', 'local_moodle_survey'));

$search = optional_param('search', '', PARAM_RAW_TRIMMED);
$status = optional_param('status', 'all', PARAM_ALPHA);

$PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));

echo $OUTPUT->header();

// Include the HTML for the survey management interface
include(__DIR__ . '/templates/manage_survey_form.php');

// Fetch and display surveys based on filters
$dbhelper = new \local_moodle_survey\model\survey();
$surveys = $dbhelper->get_surveys($search, $status);

if ($surveys) {
    include(__DIR__ . '/templates/manage_survey_table.php');
} else {
    echo html_writer::tag('div', get_string('nosurveysfound', 'local_moodle_survey'), ['class' => 'alert alert-info']);
}

echo $OUTPUT->footer();
