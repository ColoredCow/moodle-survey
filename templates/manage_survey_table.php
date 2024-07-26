<?php
$table = new html_table();
$table->head = [
    get_string('surveyname', 'local_moodle_survey'),
    get_string('surveystatus', 'local_moodle_survey'),
    get_string('actions', 'local_moodle_survey')
];

foreach ($surveys as $survey) {
    $editurl = new moodle_url('/local/moodle_survey/edit_survey.php', ['id' => $survey->id]);
    $deleteurl = new moodle_url('/local/moodle_survey/delete_survey.php', ['id' => $survey->id]);
    $actions = html_writer::link($editurl, get_string('edit', 'local_moodle_survey')) . ' | ' .
            html_writer::link($deleteurl, get_string('delete', 'local_moodle_survey'));

    $table->data[] = [
        format_string($survey->name),
        format_string($survey->status),
        $actions
    ];
}

echo html_writer::table($table);
?>
