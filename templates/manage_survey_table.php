<?php

function generate_table_head() {
    return [
        get_string('surveyname', 'local_moodle_survey'),
        get_string('surveycategory', 'local_moodle_survey'),
        get_string('surveytargetaudience', 'local_moodle_survey'),
        get_string('createdon', 'local_moodle_survey'),
        get_string('schools', 'local_moodle_survey'),
        get_string('responses', 'local_moodle_survey'),
        get_string('surveystatus', 'local_moodle_survey'),
    ];
}

function build_table_data($survey, $dbhelper, $audienceaccessdbhelper, $USER) {
    $currentDate = date('Y-m-d');
    $issurveyactive = $currentDate >= $survey->start_date && $currentDate <= $survey->end_date;
    $issurveylive = $survey->status == get_string('live', 'local_moodle_survey') && $issurveyactive;

    $surveyname = get_survey_name($survey, $issurveylive, $USER, $dbhelper);
    $surveycategory = $dbhelper->get_category_by_id($survey->category_id);
    $surveycreatedon = new DateTime($survey->created_at);
    $surveycreatedondate = $surveycreatedon->format('Y-m-d');

    $audienceaccess = $audienceaccessdbhelper->get_audience_acccess_by_survey_id($survey->id);
    $surveytargetaudience = '';
    $surveyschoolcount = 0;
    $surveyresponsescount = $dbhelper->get_survey_responses_count_by_survey_id($survey->id);

    foreach ($audienceaccess as $audience) {
        $surveyschoolid = '';
        if ($surveyschoolid != $audience->school_id) {
            $surveyschoolcount++;
        }
        $surveyschoolid = $audience->school_id;
        if (isset($audience) && isset($audience->target_audience)) {
            $surveytargetaudience = implode(", ", json_decode($audience->target_audience, true));
        }
    }

    return [
        $surveyname,
        format_string($surveycategory->label),
        format_string($surveytargetaudience),
        format_string($surveycreatedondate),
        format_string($surveyschoolcount),
        format_string($surveyresponsescount),
        get_survey_status($dbhelper, $survey),
    ];
}

function get_survey_name($survey, $issurveylive, $USER, $dbhelper) {
    $editurl = new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id]);
    $assignurl = new moodle_url('/local/moodle_survey/assign_survey.php', ['id' => $survey->id]);
    $surveyname = html_writer::link($editurl, $survey->name);

    $context = context_system::instance();
    $schoolid = get_user_school()->companyid;
    $audienceaccessdbhelper = new \local_moodle_survey\model\audience_access();
    $schoolsurvey = $audienceaccessdbhelper->get_audience_access_by_school_id_survey_id($survey->id, $schoolid);
    $issurveyassign = false;

    if (has_capability('local/moodle_survey:can-assign-survey-to-users', $context)) {
        $surveyassignstatus = $schoolsurvey->status;
        $issurveyassign = $surveyassignstatus == "assigned";
    }

    if (has_capability('local/moodle_survey:create-surveys', $context)) {
        $surveyname = html_writer::link($editurl, $survey->name);
    } elseif (!$issurveyassign && is_counsellor()) {
        $surveyname = html_writer::link($assignurl, $survey->name);
    } elseif ($issurveylive && (is_student() || is_teacher())) {
        $surveyinsights = $dbhelper->get_filling_survey_insights($survey->id, (int)$USER->id);
        $fillurl = sizeof($surveyinsights) > 0 ?
            new moodle_url('/local/moodle_survey/fill_survey/survey_insights.php', ['id' => $survey->id]) :
            new moodle_url('/local/moodle_survey/fill_survey/index.php', ['id' => $survey->id]);
        $surveyname = html_writer::link($fillurl, $survey->name);
    } elseif (is_principal() || is_counsellor()) {
        $editurl = new moodle_url('/local/moodle_survey/fill_survey/survey_analysis.php', ['id' => $survey->id]);
        $surveyname = html_writer::link($editurl, $survey->name);
    } else {
        $surveyname = html_writer::tag('span', $survey->name, ['class' => 'page-title']);
    }

    return $surveyname;
}

function get_survey_status($dbhelper, $survey) {
    $surveystatus = $dbhelper->get_survey_status($survey);
    $surveystatuscolor = '';

    switch ($surveystatus) {
        case get_string('live', 'local_moodle_survey'):
            $surveystatuscolor = 'survey-live';
            break;
        case get_string('completed', 'local_moodle_survey'):
            $surveystatuscolor = 'survey-completed';
            break;
        case get_string('draft', 'local_moodle_survey'):
            $surveystatuscolor = 'survey-draft';
            break;
        default:
            $surveystatuscolor = 'survey-live';
            break;
    }

    return html_writer::span($surveystatus, "badge badge-pill badge-color survey-status $surveystatuscolor");
}

$table = new html_table();
$table->head = generate_table_head();

$dbhelper = new \local_moodle_survey\model\survey();
$audienceaccessdbhelper = new \local_moodle_survey\model\audience_access();

foreach ($surveys as $survey) {
    $table->data[] = build_table_data($survey, $dbhelper, $audienceaccessdbhelper, $USER);
}

echo html_writer::table($table);

?>
