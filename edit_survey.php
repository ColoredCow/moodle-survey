<?php
require_once(__DIR__.'/../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$tab = optional_param('tab', 'general', PARAM_TEXT);
$context = context_system::instance();
$pagetype = "edit";

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $id, 'tab' => $tab]));
$PAGE->set_title(get_string('editsurvey', 'local_moodle_survey'));
$PAGE->set_heading(get_string('editsurvey', 'local_moodle_survey'));

$survey = $DB->get_record('cc_surveys', ['id' => $id], '*', MUST_EXIST);

if (!$survey) {
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
}

$PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/tabs.js'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));

include('includes/tabs.php');
require_once('includes/general_details_section.php');
require_once('includes/questions_scores_section.php');
require_once('includes/interpretations_section.php');
require_once('includes/validity_section.php');
require_once('includes/audience_access_section.php');
require_once('includes/footer.php');
