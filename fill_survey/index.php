<?php

require_once('../../../config.php');
require_login();

$context = context_system::instance();
$pagetype = "survey_instruction";

$id = required_param('id', PARAM_INT);
$survey = $DB->get_record('cc_surveys', ['id' => $id], '*', MUST_EXIST);
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/survey_instruction.php'));
$PAGE->set_title(get_string('surveylandingtitle', 'local_moodle_survey') . $survey->name);
$PAGE->set_heading(get_string('surveylandingtitle', 'local_moodle_survey') . $survey->name);
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
echo $OUTPUT->header();

require_once('survey_instruction.php');

echo $OUTPUT->footer();
