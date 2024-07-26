<?php
    $createurl = new moodle_url('/local/moodle_survey/create_survey.php');
    $createbutton = html_writer::div($OUTPUT->single_button($createurl, get_string('createsurvey', 'local_moodle_survey')), 'create-survey-button');
    $heading = html_writer::tag('h4', get_string('managesurvey', 'local_moodle_survey'));
    $content = $heading . ' ' . $createbutton;
    echo html_writer::tag('div', $content, ['class' => 'survey-header']);

    // Filter form
    echo html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url]);
    echo html_writer::start_div('filter-form'); // Add a CSS class for the form container
    echo html_writer::empty_tag('input', ['type' => 'date', 'placeholder' => get_string('createdat', 'local_moodle_survey'), 'class' => 'date-input']);

    $statusoptions = [
        'all' => get_string('all', 'local_moodle_survey'),
        'active' => get_string('active', 'local_moodle_survey'),
        'inactive' => get_string('inactive', 'local_moodle_survey')
    ];
    echo html_writer::select($statusoptions, 'status', $status, null, ['class' => 'status-select']);

    echo html_writer::empty_tag('input', ['type' => 'text', 'name' => 'search', 'value' => $search, 'placeholder' => get_string('search', 'local_moodle_survey'), 'class' => 'search-input']);

    echo html_writer::end_div();
    echo html_writer::end_tag('form');
?>
