<?php
require_once(__DIR__.'/../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $id]));
$PAGE->set_title(get_string('editsurvey', 'local_moodle_survey'));
$PAGE->set_heading(get_string('editsurvey', 'local_moodle_survey'));

$survey = $DB->get_record('moodle_survey', ['id' => $id], '*', MUST_EXIST);

$mform = new \local_moodle_survey\form\edit_survey_form(null, ['survey' => $survey]);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
} else if ($data = $mform->get_data()) {
    $survey->name = $data->name;
    $survey->description = $data->description;
    $survey->status = $data->status;
    $DB->update_record('moodle_survey', $survey);
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'), get_string('surveyupdated', 'local_moodle_survey'));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
