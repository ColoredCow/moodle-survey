<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
// require_capability('local/moodle_survey:create', $context);
$pagetype = "create";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/create_survey.php'));
$PAGE->set_title(get_string('createsurveycategory', 'local_moodle_survey'));
$PAGE->set_heading(get_string('createsurveycategory', 'local_moodle_survey'));

$PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/tabs.js'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));

echo $OUTPUT->header();