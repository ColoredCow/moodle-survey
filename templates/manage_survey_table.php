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
    $takingsurvey = new moodle_url('/local/moodle_survey/fill_survey/index.php', ['id' => $survey->id]);
    $deleteurl = new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $survey->id]);
    $currentDate = date('Y-m-d');
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
    $issurveylive = $currentDate >= $startDate && $currentDate <= $endDate;
    $issurveyedit = $surveystatus == get_string('draft', 'local_moodle_survey') || !$issurveylive;
    if($issurveyedit) {
        $surveyname = html_writer::link($editurl, $survey->name);
    } else {
        $surveyname = html_writer::tag('span', $survey->name, ['class' => 'survey-name']);
    }
    $takingsurvey = html_writer::link($takingsurvey, 'View');
    $surveycategory = $dbhelper->get_category_by_id($survey->category_id);
    $surveycreatedon = new DateTime($survey->created_at);
    $surveycreatedondate = $surveycreatedon->format('Y-m-d');
    $audienceaccess = $audienceaccessdbhelper->get_audience_acccess_by_survey_id($survey->id);
    $surveytargetaudience = implode(", ", json_decode($audienceaccess->target_audience, true));

    $table->data[] = [
        $surveyname,
        format_string($surveycategory->label),
        format_string($surveytargetaudience),
        format_string($surveycreatedondate),
        format_string('0'),
        format_string('0'),
        $surveytext,
        $takingsurvey
    ];
}

if(!$issurveylive) {
    $index = array_search('Taking survey', $table->head);
    if($index) {
        unset($table->head[$index]);
        foreach ($table->data as &$tabledata) {
            unset($tabledata[$index]);
        }
        
        $table->head = array_values($table->head);
        foreach ($table->data as &$tabledata) {
            $tabledata = array_values($tabledata);
        }
    }
}

echo html_writer::table($table);
?>
