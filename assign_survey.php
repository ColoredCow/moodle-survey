<?php
require_once(__DIR__.'/../../config.php');
require_login();
echo $OUTPUT->header();
require_once($CFG->dirroot . '/local/moodle_survey/lib.php');

$context = context_system::instance();
if (!has_capability('local/moodle_survey:can-assign-survey-to-users', $context)) {
    redirect(new moodle_url('/'));
}

global $PAGE;
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
$PAGE->set_title('Assign Survey');

$surveyid = required_param('id', PARAM_INT);
$surveyhelper = new \local_moodle_survey\model\survey();
$survey = $surveyhelper->get_survey_by_id($surveyid);
$school = get_school();
$audienceaccessdbhelper = new \local_moodle_survey\model\audience_access();
$schoolsurvey = $audienceaccessdbhelper->get_audience_access_by_school_id_survey_id($surveyid, $school->id);

if (count($_POST)) {
    if ($_POST['pressed_button'] == 'cancel') {
        redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
    }

    $schoolsurvey->assigned_to = json_encode($_POST['assign_to']);
    $schoolsurvey->status = 'assigned';
    $schoolsurvey->student_grade = json_encode($_POST['assign_to_students']);
    $schoolsurvey->teacher_grade = json_encode($_POST['assign_to_teachers']);

    $audienceaccessdbhelper->update_audience_access($schoolsurvey);
    redirect(new moodle_url('/local/moodle_survey/manage_survey.php'));
}

require_once('templates/assign_survey.php');
echo $OUTPUT->footer();
?>