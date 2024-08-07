<?php

require_once('../../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$dbhelper = new \local_moodle_survey\model\survey();
$survey = $dbhelper->get_survey_by_id($id);
$PAGE->set_heading('Insights from '. $survey->name);
$PAGE->set_title('Insights from ' . $survey->name);
echo $OUTPUT->header();
$surveyinsights = $dbhelper->get_filling_survey_insights($id);
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

$scoreinterpretations = [];
foreach ($surveyinsights as $index => $surveyinsight) {
    $scoreinterpretations[] = [
        'name' => $surveyinsight->label,
        'score_range' =>  $surveyinsight->score_from . ' - ' . $surveyinsight->score_to,
        'interpretation' => $surveyinsight->interpreted_as,
    ];
};

foreach ($scoreinterpretations as $scoreinterpretation) {
    $scroeinterpretationtable->data[] = [
        $scoreinterpretation['name'],
        $scoreinterpretation['score_range'],
        $scoreinterpretation['interpretation'],
    ];
}

$scores = [];
foreach ($surveyinsights as $index => $surveyinsight) {
    $scores[] = [
        'name' => $surveyinsight->label,
        'score' => $surveyinsight->score,
    ];
}

foreach ($scores as $score) {
    $table->data[] = [
        $score['name'],
        $score['score'],
    ];
}
foreach ($scroeinterpretations as $scroeinterpretation) {
    $scroeinterpretationtable->data[] = [
        $scroeinterpretation['name'],
        $scroeinterpretation['score_range'],
        $scroeinterpretation['interpretation'],
    ];
}

$statusoptions = [];
foreach ($surveyinsights as $surveyinsight) {
    $statusoptions[$surveyinsight->slug] = $surveyinsight->label;
}

?>

<div class="learning-survey-insights-section">
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