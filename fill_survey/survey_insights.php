<?php

require_once('../../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
$PAGE->set_heading('Insights from '. $survey->name);
$PAGE->set_title('Insights from ' . $survey->name);
echo $OUTPUT->header();
$surveyinsightsresponse = $dbhelper->get_filling_survey_insights($id, $USER->id);
$questioncategories = $dbhelper->get_all_question_categories();
foreach($surveyinsightsresponse as $surveyinsight) {
    $surveyresponses = json_decode($surveyinsight->response);
}
$surveyinsightsdata = $surveyresponses->surveyData;

$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');

$table = new html_table();
$scroeinterpretationtable = new html_table();
$table->head = [
    'Competency',
    'Score',
];
$scroeinterpretationtable->head = [
    'Competency',
    'Score Range',
    'Interpreted as',
];

foreach ($surveyinsightsdata->categoriesScores as $key => $categoriesscore) {
    $table->data[] = [
        $categoriesscore[0]->catgororySlug,
        $categoriesscore[0]->score,
    ];
}

foreach ($surveyinsightsdata->interpretations as $surveyinsightsdatainterpretation) {
    $scroeinterpretationtable->data[] = [
        $surveyinsightsdatainterpretation[0]->catgororySlug,
        $surveyinsightsdatainterpretation[0]->range[0],
        $surveyinsightsdatainterpretation[0]->text,
    ];
}

$statusoptions = [];
foreach ($questioncategories as $questioncategorie) {
    $statusoptions[$questioncategorie->slug] = $questioncategorie->label;
}
?>

<div class="survey-insights-section">
    <div class="accordion">
        <div class="accordion-header score-interpretation-details">
            <?php
                echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">';
            ?>
            <h5><?php echo get_string('yourscore', 'local_moodle_survey'); ?></h5>
        </div>
        <div class="accordion-body survey-insights-score">
            <?php echo html_writer::table($table); ?>
        </div>
    </div>

    <div class="accordion score-interpretation-section">
        <div class="accordion-header score-interpretation-details justify-content-between">
            <div class="d-flex">
                <?php
                    echo '<img src="' . $iconurl . '" alt="Icon" class="accordion-icon">';
                ?>
                <h5><?php echo get_string('scoreinterpretation', 'local_moodle_survey'); ?></h5>
            </div>
            <?php echo html_writer::select($statusoptions, 'status', $status, null, ['class' => 'status-select', 'id' => 'status-select']); ?>
        </div>
        <div class="accordion-body survey-insights-score">
            <?php echo html_writer::table($scroeinterpretationtable); ?>
        </div>
    </div>
</div>


<?php
echo $OUTPUT->footer();
?>