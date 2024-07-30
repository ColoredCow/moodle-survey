<?php

require_once('../../../config.php');
require_login();

$PAGE->set_heading('Insights from Modes of Learning survey');
echo $OUTPUT->header();
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

$scroeinterpretations = [
    [
        'name' => 'Frequency',
        'score_range' => '0-10',
        'interpretation' => 'Underdeveloped<br>Your experience with and satisfaction towards different modes of learning is quite low. You may benefit from exploring new learning methods or resources to enhance your educational experience.',
    ],
    [
        'name' => '',
        'score_range' => '11-20',
        'interpretation' => 'Needs Improvement<br>You have some familiarity with different learning modes, but there is significant room for improvement. Consider trying out different platforms or techniques to find what works best.',
    ],
    [
        'name' => '',
        'score_range' => '21-30',
        'interpretation' => 'Moderate<br>You have a moderate level of experience and satisfaction with various learning modes. There are areas that could be enhanced, but you are on the right track.'
    ],
];

$scores = [
        [
            'name' => 'Frequency',
            'score' => 20,
        ],
        [
            'name' => 'Effectiveness',
            'score' => 16,
        ],
        [
            'name' => 'Engagement',
            'score' => 10,
        ],
    ];

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

$statusoptions = [
    'all' => get_string('all', 'local_moodle_survey'),
    'live' => get_string('live', 'local_moodle_survey'),
    'completed' => get_string('completed', 'local_moodle_survey'),
];
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