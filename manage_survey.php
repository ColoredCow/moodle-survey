<?php

require_once('../../config.php');
require_login();

initialize_page();
echo $OUTPUT->header();

$filters = get_filters();

// Fetch surveys
$surveys = fetch_surveys($filters);

display_page($surveys);

/**
 * Initializes the page context and resources.
 */
function initialize_page() {
    global $PAGE;

    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url(new moodle_url('/local/moodle_survey/manage_survey.php'));
    $PAGE->set_title(get_string('managesurvey', 'local_moodle_survey'));

    $PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));
    $PAGE->requires->js(new moodle_url('/local/moodle_survey/js/tabs.js'));
    $PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
}

/**
 * Retrieves the filter parameters from the request.
 *
 * @return array
 */
function get_filters() {
    $search = optional_param('search', '', PARAM_RAW_TRIMMED);
    $status = optional_param('status', '', PARAM_ALPHA);
    $surveycategory = optional_param('category', '', PARAM_ALPHANUMEXT);
    $createdon = optional_param('createdon', '', PARAM_RAW_TRIMMED);

    return [
        'search' => $search,
        'status' => $status,
        'surveycategory' => $surveycategory,
        'createdon' => $createdon,
    ];
}

/**
 * Fetches surveys based on the provided filters.
 *
 * @param array $filters
 * @return array
 */
function fetch_surveys($filters) {
    $dbhelper = new \local_moodle_survey\model\survey();
    return $dbhelper->get_surveys($filters);
}

/**
 * Displays the survey management page.
 *
 * @param array $surveys
 */
function display_page($surveys) {
    global $OUTPUT;

    // Include the HTML for the survey management interface
    include(__DIR__ . '/templates/manage_survey_header.php');

    if ($surveys) {
        include(__DIR__ . '/templates/manage_survey_table.php');
    } else {
        echo html_writer::tag('div', get_string('nosurveysfound', 'local_moodle_survey'), ['class' => 'alert alert-info']);
    }

    echo $OUTPUT->footer();
}
