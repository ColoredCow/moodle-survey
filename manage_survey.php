<?php

require_once('../../config.php');
require_login();

$dbhelper = new \local_moodle_survey\model\survey();
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/manage_survey.php'));
$PAGE->set_title(get_string('managesurvey', 'local_moodle_survey'));

$search = optional_param('search', '', PARAM_RAW_TRIMMED);
$status = optional_param('status', '', PARAM_ALPHA);
$surveycategory = optional_param('category', '', PARAM_ALPHANUMEXT);
$createddon = optional_param('createdon', '', PARAM_RAW_TRIMMED);
$filters = [
    'search' => $search,
    'status' => $status,
    'surveycategory' => $surveycategory,
];

$PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));

echo $OUTPUT->header();

// Include the HTML for the survey management interface
include(__DIR__ . '/templates/manage_survey_header.php');

// Fetch and display surveys based on filters
$surveys = $dbhelper->get_surveys($filters);

if ($surveys) {
    include(__DIR__ . '/templates/manage_survey_table.php');
} else {
    echo html_writer::tag('div', get_string('nosurveysfound', 'local_moodle_survey'), ['class' => 'alert alert-info']);
}

echo $OUTPUT->footer();
