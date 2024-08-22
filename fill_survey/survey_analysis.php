<?php

require_once('../../../config.php');
require_login();

use core\chart_pie;
use core\chart_series;

initialize_page();
$id = required_param('id', PARAM_INT);
$surveydbhelper = $dbhelper = new \local_moodle_survey\model\survey();
$survey = $surveydbhelper->get_survey_by_id($id);

$statusoptions = get_string('surveyinsighttypes', 'local_moodle_survey');
$questioncategories = get_question_categories($surveydbhelper);
$currentinsighttype = optional_param('insighttype', 'teacher', PARAM_ALPHA);
$questioncategory = optional_param('category', 'well-being', PARAM_ALPHA);

$url = new moodle_url('/local/moodle_survey/fill_survey/survey_analysis.php', ['id' => $id]);
$downarrowiconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');

echo $OUTPUT->header();
echo render_survey_analysis_title($id, $url, $currentinsighttype, $questioncategory, $statusoptions);
echo render_survey_instruction($survey, $downarrowiconurl);
echo render_survey_insights($url, $downarrowiconurl, $id, $questioncategories, $questioncategory, $currentinsighttype);
echo $OUTPUT->footer();

function initialize_page() {
    global $PAGE;

    $context = context_system::instance();
    $PAGE->set_context($context);
    $PAGE->set_url(new moodle_url('/local/moodle_survey/fill_survey/survey_analysis.php'));
    $PAGE->set_title(get_string('selanalysis', 'local_moodle_survey'));

    $PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
}

function render_survey_analysis_title($id, $url, $currentinsighttype, $questioncategory, $statusoptions) {
    $html = html_writer::start_tag('form', ['method' => 'get', 'action' => $url, 'id' => 'filter-form']);
    $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-title d-flex justify-content-between']);
        $html .= html_writer::tag('h3', 'Surveys/' . get_string('selanalysis', 'local_moodle_survey'), ['class' => 'survey-analysis-heading']);
        $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-title-actions']);
            $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $id]);
            $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'category', 'value' => $questioncategory]);
            $html .= html_writer::select($statusoptions, 'insighttype', $currentinsighttype, null, ['class' => 'status-select', 'id' => 'survey-insight-type']);
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('form');
    return $html;
}

function render_survey_instruction($survey, $downarrowiconurl) {
    $html = html_writer::start_tag('div', ['class' => '', 'id' => "accordion"]);
        $html .= html_writer::start_tag('div', ['class' => 'accordion active survey-description-container']);
            $html .= html_writer::start_tag('div', ['class' => 'accordion-header accordion-header-section survey-about-container']);
                $html .= html_writer::start_tag('img', ['src' => $downarrowiconurl, 'class' => 'accordion-icon']);
                $html .= html_writer::tag('h3', get_string('whatsurveyabout', 'local_moodle_survey'), ['class' => 'survey-about-heading mb-0 ml-2']);
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', ['class' => 'accordion-body pl-5']);
                $html .= html_writer::tag('p', $survey->description);
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}

function render_survey_insights($url, $downarrowiconurl, $id, $questioncategories, $questioncategory, $currentinsighttype) {
    $html = html_writer::start_tag('div', ['id' => "accordion"]);
        $html .= html_writer::start_tag('div', ['class' => 'accordion active']);
            $html .= html_writer::start_div('d-flex justify-content-between mb-5');
                $html .= html_writer::start_tag('div', ['class' => 'd-flex align-items-center']);
                    $html .= html_writer::tag('img', '', ['src' => $downarrowiconurl, 'class' => 'accordion-icon mr-2']);
                    $html .= html_writer::tag('h3', 'Insights', ['class' => 'survey-about-heading mb-0']);
                $html .= html_writer::end_tag('div');
                $html .= get_question_category_filter($url, $id, $questioncategories, $questioncategory, $currentinsighttype);
            $html .= html_writer::end_div();
            $html .= html_writer::start_tag('div', ['class' => 'accordion-body']);
                $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-chart-container']);
                    $html .= get_bar_chart_labels();
                    $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-chart-body']);
                        $html .= render_survey_analysis_charts();
                    $html .= html_writer::end_tag('div');
                $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}

function render_survey_analysis_charts() {
    $html = '';
    for ($i = 0; $i < 3; $i++) {
        $html .= render_survey_analysis_chart();
    }
    return $html;
}

function render_survey_analysis_chart() {
    global $OUTPUT, $CFG;

    $html = html_writer::start_tag('div', ['class' => 'survey-analysis-chart']);
    $CFG->chart_colorset = get_string('chartcolorset', 'local_moodle_survey');
    $pieChart = new chart_pie();
    $pieChartData = [rand(0, 100), rand(0, 100), rand(0, 100), rand(0, 100)];
    $pieChart->set_legend_options(['display' => false]);
    $series = new chart_series('Insights', $pieChartData);
    $pieChartLabels = ["Underdeveloped", "Developing", "Progressing", "Remarkable"];
    $pieChart->set_labels($pieChartLabels);
    $pieChart->add_series($series);
    $pieChart->set_title('Survey Data');
    $horizontalBarChartHtml = $OUTPUT->render_chart($pieChart, false);
    $html .= $horizontalBarChartHtml;
    $html .= html_writer::end_tag('div');
    return $html;
}

function get_question_category_filter($url, $id, $questioncategories, $questioncategory, $currentinsighttype) {
    $html = html_writer::start_tag('form', ['method' => 'get', 'action' => $url, 'id' => 'filter-category-form', 'class' => 'd-flex align-items-center']);
    $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'id', 'value' => $id]);
    $html .= html_writer::empty_tag('input', ['type' => 'hidden', 'name' => 'insighttype', 'value' => $currentinsighttype]);
    $html .= html_writer::select($questioncategories, 'category', $questioncategory, null, ['class' => 'status-select', 'id' => 'question-category-select']);
    $html .= html_writer::end_tag('form');
    return $html;
}

function get_question_categories($surveydbhelper) {
    $questioncategories = $surveydbhelper->get_all_question_categories();
    $categories = [];
    foreach ($questioncategories as $category) {
        $categories[$category->slug] = $category->label;
    }
    return $categories;
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
                        $html .= html_writer::tag('span', $value['label'], ['class' => 'pie-chart-label']);
                    $html .= html_writer::end_div();
                $html .= html_writer::end_div();
            }
        $html .= html_writer::end_div();
    $html .= html_writer::end_div();
    return $html;
}

function get_bar_chart_colors($key) {
    $colors = [
        0 => 'primary-chart-color',
        1 => 'primary10-chart-color',
        2 => 'primary100-chart-color',
        3 => 'secondary-chart-color',
    ];
    return $colors[$key] ?? 'default-chart-color'; // Provide a default color if not found
}
