<?php

require_once('../../../config.php');
require_login();

$context = context_system::instance();
$pagetype = "survey_instruction";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/survey_instruction.php'));
$PAGE->set_title(get_string('surveylandingheading', 'local_moodle_survey'));
$PAGE->set_heading(get_string('surveylandingheading', 'local_moodle_survey'));
echo $OUTPUT->header();

require_once('survey_instruction.php');

echo $OUTPUT->footer();
