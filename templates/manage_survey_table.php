<?php
$table = new html_table();
$dbhelper = new \local_moodle_survey\model\survey();
$audienceaccessdbhelper = new \local_moodle_survey\model\audience_access();
$table->head = [
    get_string('surveyname', 'local_moodle_survey'),
    get_string('surveycategory', 'local_moodle_survey'),
    get_string('surveytargetaudience', 'local_moodle_survey'),
    get_string('createdon', 'local_moodle_survey'),
    get_string('schools', 'local_moodle_survey'),
    get_string('responses', 'local_moodle_survey'),
    get_string('surveystatus', 'local_moodle_survey'),
    'Taking survey',
];

foreach ($surveys as $survey) {
    $editurl = new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id]);
    $deleteurl = new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $survey->id]);
    $currentDate = date('Y-m-d');
    $issurveyactive = $currentDate >= $survey->start_date && $currentDate <= $endDate = $survey->end_date;
    $issurveylive = $surveystatus == get_string('draft', 'local_moodle_survey') || !$issurveyactive;
    $surveyname = html_writer::link($editurl, $survey->name);
    if(has_capability('local/moodle_survey:create-surveys', context_system::instance())) {
        $surveyname = html_writer::link($editurl, $survey->name);
    } else {
        $surveyname = html_writer::tag('span', $survey->name, ['class' => 'page-title']);
    }
    $takingsurvey = get_taking_survey_link($survey, $issurveylive, $dbhelper, $USER);
    $surveycategory = $dbhelper->get_category_by_id($survey->category_id);
    $surveycreatedon = new DateTime($survey->created_at);
    $surveycreatedondate = $surveycreatedon->format('Y-m-d');
    $audienceaccess = $audienceaccessdbhelper->get_audience_acccess_by_survey_id($survey->id);
    $surveytargetaudience = '';
    foreach ($audienceaccess as $audience) {
        if (isset($audience) && isset($audience->target_audience)) {
            $surveytargetaudience = implode(", ", json_decode($audience->target_audience, true));
            continue;
        }
    }

    $table->data[] = [
        $surveyname,
        format_string($surveycategory->label),
        format_string($surveytargetaudience),
        format_string($surveycreatedondate),
        format_string('0'),
        format_string('0'),
        get_survey_status($dbhelper, $survey),
        $takingsurvey
    ];
}

echo html_writer::table($table);

function get_taking_survey_link($survey, $issurveylive, $dbhelper, $USER) {
    $surveyinsights = $dbhelper->get_filling_survey_insights($survey->id, (int)$USER->id);
    $buttonclass = 'view-btn';
    if(sizeof($surveyinsights) > 0) {
        $takingsurveyurl = new moodle_url('/local/moodle_survey/fill_survey/survey_insights.php', ['id' => $survey->id]);
    } else if ($issurveylive && (is_student() || is_teacher())) {
        $takingsurveyurl = new moodle_url('/local/moodle_survey/fill_survey/index.php', ['id' => $survey->id]);
    } else {
        $buttonclass = 'view-btn-disabled disabled';
    }
    $takingsurvey = html_writer::link($takingsurveyurl, 'View', ['class' => $buttonclass]);

    return $takingsurvey;
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
    $surveytext = html_writer::span($surveystatus, "badge badge-pill badge-color survey-status $surveystatuscolor");

    return $surveytext;
}

?>
