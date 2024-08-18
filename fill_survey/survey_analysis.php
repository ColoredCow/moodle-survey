<?php

require_once('../../../config.php');
require_login();

use core\chart_pie;
use core\chart_series;
$context = context_system::instance();
$id = required_param('id', PARAM_INT);
$survey = $DB->get_record('cc_surveys', ['id' => $id], '*', MUST_EXIST);
$PAGE->set_title(get_string('selanalysis', 'local_moodle_survey'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
echo $OUTPUT->header();
echo render_survey_analysis_title($PAGE);
echo render_survey_instruction($survey);
echo render_survey_insights($OUTPUT, $CFG);


function render_survey_analysis_title($PAGE) {
    $statusoptions = [
        'teacher' => 'Teachers Insights',
        'student' => 'Student Insights',
    ];
    $html = html_writer::start_tag('div', array('class' => 'survey-analysis-title d-flex justify-content-between'));
        $html .= html_writer::tag('h3', 'Surveys/' .  get_string('selanalysis', 'local_moodle_survey') .'', array('class' => 'survey-analysis-heading'));
        $html .= html_writer::start_tag('div', array('class' => 'survey-analysis-title-actions'));
            $html .= html_writer::start_tag('form', ['method' => 'get', 'action' => $PAGE->url, 'id' => 'filter-form']);
                $html .= html_writer::select($statusoptions, 'status', key($statusoptions), null, ['class' => 'status-select', 'id' => 'status-select']);
            $html .= html_writer::end_tag('form');
        $html .= html_writer::end_div();
    $html .= html_writer::end_div();
    return $html;
}

function render_survey_instruction($survey) {
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $html = html_writer::start_tag('div', array('class' => '', 'id'=>"accordion"));
        $html .= html_writer::start_tag('div', array('class' => 'accordion active survey-description-container'));
            $html .= html_writer::start_tag('div', array('class' => 'accordion-header accordion-header-section survey-about-container'));
                $html .= html_writer::start_tag('img', array('src' => $iconurl, 'class' => 'accordion-icon'));
                $html .= html_writer::tag('h3', get_string('whatsurveyabout', 'local_moodle_survey'), array('class' => 'survey-about-heading mb-0 ml-2'));
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', array('class' => 'accordion-body pl-5'));
                $html .= html_writer::tag('p', $survey->description);
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}


function render_survey_insights($OUTPUT, $CFG) {
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $html = html_writer::start_tag('div', array('id'=>"accordion"));
        $html .= html_writer::start_tag('div', array('class' => 'accordion active'));
            $html .= html_writer::start_tag('div', array('class' => 'accordion-header accordion-header-section survey-about-container'));
                $html .= html_writer::start_tag('img', array('src' => $iconurl, 'class' => 'accordion-icon'));
                $html .= html_writer::tag('h3', 'Insights', array('class' => 'survey-about-heading mb-0 ml-2'));
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', array('class' => 'accordion-body'));
            $html .= html_writer::start_tag('div', array('class' => 'survey-analysis-chart-container'));
            $html .= get_bar_chart_labels();
            $html .= html_writer::start_tag('div', array('class' => 'survey-analysis-chart-body'));
                    for ($i=0; $i < 3; $i++) { 
                        $html .= render_survey_analysis_chart($OUTPUT, $CFG);
                    }
                $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}

function get_bar_chart_labels() {
    $charlabels = get_string('chartlabels', 'local_moodle_survey');
    $html = html_writer::start_div('pie-chart-labels-container d-flex align-items-center justify-content-center');
        $html .= html_writer::start_div('d-flex align-items-center');
                foreach ($charlabels as $key => $value) {
                    $html .= html_writer::start_div('pie-chart-labels-section d-flex ');
                        $html .= html_writer::start_div('pie-chart-label-color ' . get_bar_chart_colors($key));
                        $html .= html_writer::end_div();
                        $html .= html_writer::start_div('pie-chart-label-text');
                            $html .= html_writer::tag('span', $value['label'], array('class' => 'pie-chart-label'));
                        $html .= html_writer::end_div();
                    $html .= html_writer::end_div();
                }
        $html .= html_writer::end_div();
    $html .= html_writer::end_div();
    return $html;
}

function render_survey_analysis_chart($OUTPUT, $CFG) {
    $html = html_writer::start_tag('div', array('class' => 'survey-analysis-chart'));
        $CFG->chart_colorset = get_string('chartcolorset', 'local_moodle_survey');
        $pieChart = new chart_pie();
        $pieChartData = [rand(0,100), rand(0,100), rand(0,100),rand(0,100)];
        $series = new chart_series('Insights', $pieChartData);
        $pieChart->add_series($series);
        $pieChart->set_title('Survey Data');
    $horizontalBarChartHtml = $OUTPUT->render_chart($pieChart, false);
    $html .= $horizontalBarChartHtml;
    $html .= html_writer::end_tag('div');
    return $html;
}

function get_bar_chart_colors($key) {
    switch($key){
        case 0:
            return 'primary-chart-color';
        case 1:
            return 'primary10-chart-color';
        case 2:
            return 'primary100-chart-color';
        case 3:
            return 'secondary-chart-color';
    }
}

echo $OUTPUT->footer();
