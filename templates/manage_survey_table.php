<?php
$table = new html_table();
$dbhelper = new \local_moodle_survey\model\survey();
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
    $takingsurvey = new moodle_url('/local/moodle_survey/modes_of_learning_survey/index.php');
    $deleteurl = new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $survey->id]);
    $surveyname = html_writer::link($editurl, $survey->name);
    $takingsurvey = html_writer::link($takingsurvey, 'View');
    $surveycategory = $dbhelper->get_surver_category_by_id($survey->category_id);
    $surveycreatedon = new DateTime($survey->created_at);
    $surveycreatedondate = $surveycreatedon->format('Y-m-d');

    $table->data[] = [
        $surveyname,
        format_string($surveycategory->label),
        format_string('student'),
        format_string($surveycreatedondate),
        format_string('0'),
        format_string('0'),
        format_string($survey->status),
        $takingsurvey
    ];
}

echo html_writer::table($table);
?>
