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

foreach ($surveyinsightsresponse as $surveyinsight) {
    $surveyresponses = json_decode($surveyinsight->response);
}

if (isset($surveyresponses->surveyData)) {
    $surveyinsightsdata = $surveyresponses->surveyData;
} else {
    $surveyinsightsdata = (object)[ 'categoriesScores' => [], 'interpretations' => [] ];
}

$iconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');

$table = new html_table();
$table->head = [
    get_string('competency', 'local_moodle_survey'),
    get_string('score', 'local_moodle_survey'),
];

$defaultCategory = reset($surveyinsightsdata->categoriesScores);
$categorySlugs = [];
foreach ($surveyinsightsdata->categoriesScores as $categoriesscore) {
    $table->data[] = [
        $categoriesscore->catgororySlug,
        $categoriesscore->score,
    ];
    $categorySlugs[] = $categoriesscore->catgororySlug;
}

$interpretationsData = [];
foreach ($surveyinsightsdata->interpretations as $interpretation) {
    foreach ($interpretation as $key => $value) {
        $interpretationsData[$value->catgororySlug] = [
            'description' => $value->description,
            'range' => $value->range,
            'text' => $value->text,
        ];
    }
}

$statusoptions = [];
foreach ($questioncategories as $questioncategorie) {
    if(in_array($questioncategorie->label, $categorySlugs)) {
        $statusoptions[$questioncategorie->slug] = $questioncategorie->label;
    }
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
            <?php echo html_writer::select($statusoptions, 'status', key($statusoptions), null, ['class' => 'status-select', 'id' => 'status-select']); ?>
        </div>
        <div class="accordion-body survey-insights-score" id="score-interpretation-table">
            <?php
                $defaultInterpretation = $interpretationsData[key($statusoptions)] ?? ['range' => '', 'text' => ''];
                echo '
                <div class="table-responsive">
                    <table class="generaltable">
                        <thead>
                            <tr>
                                <th>Competency</th>
                                <th>Score Range</th>
                                <th>Interpreted as</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>' . array_values($statusoptions) . '</td>
                                <td>' . $defaultInterpretation['range'] . '</td>
                                <td class="interpretation-as">' . $defaultInterpretation['text'] . '<br/>' . $defaultInterpretation['description'] . '</td>
                            </tr>
                        </tbody>
                    </table>
                </div>';
            ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status-select');
    const tableContainer = document.getElementById('score-interpretation-table');
    
    const interpretationsData = <?php echo json_encode($interpretationsData); ?>;
    
    statusSelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        const data = interpretationsData[selectedCategory];
        const questionCategory = selectedCategory && selectedCategory[0].toUpperCase() + selectedCategory.slice(1);

        if (data) {
            const tableHtml = generateTableHtml(questionCategory, data.range, data.text, data.description);
            tableContainer.innerHTML = tableHtml;
        } else {
            tableContainer.innerHTML = generateNoDataHtml();
        }
    });

    function generateTableHtml(questionCategory, range, interpretation, description) {
        return `
        <div class="table-responsive">
            <table class="generaltable">
                <thead>
                    <tr>
                        <th>Competency</th>
                        <th>Score Range</th>
                        <th>Interpreted as</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${questionCategory}</td>
                        <td>${range}</td>
                        <td class="interpretation-as">${interpretation}<br/>${description}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        `;
    }

    function generateNoDataHtml() {
        return `
        <div class="table-responsive">
            <table class="generaltable">
                <thead>
                    <tr>
                        <th>Competency</th>
                        <th>Score Range</th>
                        <th>Interpreted as</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" class="text-center">
                            <div class="alert alert-info">No Data Available.</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        `;
    }

    statusSelect.dispatchEvent(new Event('change'));
});
</script>

<?php
echo $OUTPUT->footer();
?>
