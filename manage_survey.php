<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
// require_capability('local/moodle_survey:manage', $context);

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/manage_survey.php'));
$PAGE->set_title(get_string('managesurvey', 'local_moodle_survey'));

$search = optional_param('search', '', PARAM_RAW_TRIMMED);
$status = optional_param('status', 'all', PARAM_ALPHA);

$PAGE->requires->css(new moodle_url('/local/moodle_survey/styles.css'));

echo $OUTPUT->header();

// Add Create Survey button
$createurl = new moodle_url('/local/moodle_survey/create_survey.php');
$createbutton = html_writer::div($OUTPUT->single_button($createurl, get_string('createsurvey', 'local_moodle_survey')), 'create-survey-button');
$heading = html_writer::tag('h4', get_string('managesurvey', 'local_moodle_survey'));
$content = $heading . ' ' . $createbutton;
echo html_writer::tag('div', $content, array('class' => 'survey-header'));

// Filter form
echo html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url]);
echo html_writer::start_div('filter-form'); // Add a CSS class for the form container
echo html_writer::empty_tag('input', ['type' => 'date', 'placeholder' => get_string('createdat', 'local_moodle_survey'), 'class' => 'date-input']);

$statusoptions = [
    'all' => get_string('all', 'local_moodle_survey'),
    'active' => get_string('active', 'local_moodle_survey'),
    'inactive' => get_string('inactive', 'local_moodle_survey')
];
echo html_writer::select($statusoptions, 'status', $status, ['class' => 'status-select']); // Add a CSS class for the select element

echo html_writer::empty_tag('input', ['type' => 'text', 'name' => 'search', 'value' => $search, 'placeholder' => get_string('search', 'local_moodle_survey'), 'class' => 'search-input']);

echo html_writer::end_div();
echo html_writer::end_tag('form');


// Fetch and display surveys based on filters
$sql = "SELECT * FROM mdl_moodle_survey WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND name LIKE :search";
    $params['search'] = "%$search%";
}

if ($status !== 'all') {
    $sql .= " AND status = :status";
    $params['status'] = $status;
}


$surveys = $DB->get_records_sql($sql, $params);

if ($surveys) {
    $table = new html_table();
    $table->head = [
        get_string('surveyname', 'local_moodle_survey'),
        get_string('surveystatus', 'local_moodle_survey'),
        get_string('actions', 'local_moodle_survey')
    ];

    foreach ($surveys as $survey) {
        $editurl = new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id]);
        $deleteurl = new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $survey->id]);
        $actions = html_writer::link($editurl, get_string('edit', 'local_moodle_survey')) . ' | ' .
                   html_writer::link($deleteurl, get_string('delete', 'local_moodle_survey'));

        $table->data[] = [
            format_string($survey->name),
            format_string($survey->status),
            $actions
        ];
    }

    echo html_writer::table($table);
} else {
    echo html_writer::tag('div', get_string('nosurveysfound', 'local_moodle_survey'), ['class' => 'alert alert-info']);
}

echo $OUTPUT->footer();
