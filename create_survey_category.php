<?php

require_once('../../config.php');
require_login();

$context = context_system::instance();
// require_capability('local/moodle_survey:create', $context);
$pagetype = "create_survey_category";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/create_survey_category.php'));
$PAGE->set_title(get_string('createsurvey', 'local_moodle_survey'));
$PAGE->set_heading(get_string('createsurvey', 'local_moodle_survey'));

require_once('includes/tabs.php');
require_once('includes/footer.php');
