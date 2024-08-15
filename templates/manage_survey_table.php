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
    $issurveylive = $currentDate >= $survey->start_date && $currentDate <= $endDate = $survey->end_date;
    $issurveyedit = $surveystatus == get_string('draft', 'local_moodle_survey') || !$issurveylive;
    $surveyname = html_writer::link($editurl, $survey->name);
    // For now, user can also edit the live survey
    // if($issurveyedit) {
    //     $surveyname = html_writer::link($editurl, $survey->name);
    // } else {
    //     $surveyname = html_writer::tag('span', $survey->name, ['class' => 'page-title']);
    // }
    $takingsurvey = get_taking_survey_link($survey, $issurveyedit, $dbhelper, $USER);
    $surveycategory = $dbhelper->get_category_by_id($survey->category_id);
    $surveycreatedon = new DateTime($survey->created_at);
    $surveycreatedondate = $surveycreatedon->format('Y-m-d');
    $audienceaccess = $audienceaccessdbhelper->get_audience_acccess_by_survey_id($survey->id);
    $surveytargetaudience = '';
    if (isset($audienceaccess) && isset($audienceaccess->target_audience)) {
        $surveytargetaudience = implode(", ", json_decode($audienceaccess->target_audience, true));
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

function get_taking_survey_link($survey, $issurveyedit, $dbhelper, $USER) {
    $surveyinsights = $dbhelper->get_filling_survey_insights($survey->id, (int)$USER->id);
    if(sizeof($surveyinsights) > 0) {
        $takingsurveyurl = new moodle_url('/local/moodle_survey/fill_survey/survey_insights.php', ['id' => $survey->id]);
    } else {
        $takingsurveyurl = new moodle_url('/local/moodle_survey/fill_survey/index.php', ['id' => $survey->id]);
    }
    $takingsurvey = !$issurveyedit ? html_writer::link($takingsurveyurl, 'View', ['class' => 'view-btn']) : html_writer::span('View', 'disable-view-btn');

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
