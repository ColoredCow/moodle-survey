<?php
require_once(__DIR__.'/../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $id]));

if (confirm_sesskey()) {
    $DB->delete_records('moodle_survey', ['id' => $id]);
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'), get_string('surveydeleted', 'local_moodle_survey'));
}

echo $OUTPUT->header();
echo $OUTPUT->confirm(get_string('confirmdelete', 'local_moodle_survey'), new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $id, 'sesskey' => sesskey()]), new moodle_url('/local/saishiko_surveys/manage_survey.php'));
echo $OUTPUT->footer();
