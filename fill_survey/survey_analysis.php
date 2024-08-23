<?php

require_once('../../../config.php');
require_login();

use core\chart_pie;
use core\chart_series;
use core\chart_bar;

initialize_page();
$id = required_param('id', PARAM_INT);
$surveydbhelper = $dbhelper = new \local_moodle_survey\model\survey();
$survey = $surveydbhelper->get_survey_by_id($id);

$statusoptions = get_string('surveyinsighttypes', 'local_moodle_survey');
$questioncategories = get_question_categories($surveydbhelper);
$currentinsighttype = optional_param('insighttype', 'teacher', PARAM_ALPHA);
$questioncategory = optional_param('category', array_key_first($questioncategories), PARAM_ALPHANUMEXT);
$interpretationdata = $surveydbhelper->get_interpretations_data_by_survey_id_and_question_category_id($id, $currentinsighttype);

$url = new moodle_url('/local/moodle_survey/fill_survey/survey_analysis.php', ['id' => $id]);
$downarrowiconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');

echo $OUTPUT->header();
echo render_survey_analysis_title($id, $url, $currentinsighttype, $questioncategory, $statusoptions);
echo render_survey_instruction($survey, $downarrowiconurl);
echo render_survey_insights($url, $downarrowiconurl, $id, $questioncategories, $questioncategory, $currentinsighttype, $interpretationdata);
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

function render_survey_insights($url, $downarrowiconurl, $id, $questioncategories, $questioncategory, $currentinsighttype, $interpretationdata) {
    $calculatedinterpretationdata = calculate_pie_chart_data_by_question_category($interpretationdata, $questioncategory);
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
                    $html .= get_bar_chart_labels($calculatedinterpretationdata);
                    $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-chart-body justify-content-center']);
                        $html .= render_survey_analysis_chart($calculatedinterpretationdata, $questioncategory);
                    $html .= html_writer::end_tag('div');
                        $html .= render_survey_questions_analysis_horizontal_chart($interpretationdata, $questioncategory);
                $html .= html_writer::end_tag('div');
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}

function render_survey_analysis_chart($calculatedinterpretationdata, $questioncategory) {
    global $OUTPUT, $CFG;
    
    // Create and configure the pie chart
    $CFG->chart_colorset = get_string('chartcolorset', 'local_moodle_survey');
    $pieChart = new chart_pie();
    $pieChart->set_legend_options(['display' => true]);
    $series = new chart_series('', $calculatedinterpretationdata['pieChartData']);
    $pieChart->set_labels($calculatedinterpretationdata['pieChartLabels']);
    $pieChart->add_series($series);
    $pieChart->set_title(ucfirst($questioncategory));
    $pieChart->set_legend_options(['display' => false]);

    // Render the pie chart
    $html = html_writer::start_tag('div', ['class' => 'survey-analysis-chart']);
    if (empty($calculatedinterpretationdata['pieChartData'])) {
        $html .= html_writer::tag('div', get_string('nochartexist', 'theme_academi'), ['class' => 'no-chart-found alert alert-info']);
    } else {
        $html .= $OUTPUT->render_chart($pieChart, false);
    }
    $html .= html_writer::end_tag('div');
    
    return $html;
}

function render_survey_questions_analysis_horizontal_chart($interpretationdata, $questioncategory) {
    global $OUTPUT, $CFG;
    
    $html = '';
    
    // Loop through each question in the interpretation data
    $CFG->chart_colorset = ['#F16824'];
    $html .= html_writer::start_div('horizontal-chart');
    $questioncount = 1;
    foreach ($interpretationdata as $data) {
        $responses = json_decode($data->survey_responses, true);

        foreach ($responses as $responseValue) {
            if (is_array($responseValue) && isset($responseValue['questionCategorySlug']) && $responseValue['questionCategorySlug'] == $questioncategory) {
                
                // Calculate the bar chart data for this question
                $calculateddata = calculate_bar_chart_data_by_question_category($responseValue, $questioncategory);

                // Create a bar chart for this question
                $barChart = new chart_bar();
                $barChart->set_legend_options(['display' => false]);
                $barChart->set_horizontal(true);
                $series = new chart_series('', $calculateddata['barChartData']);
                $barChart->set_labels($calculateddata['barChartLabels']);
                $barChart->add_series($series);

                $html .= html_writer::tag('h4', 'Q' . $questioncount .': ' . $responseValue['question'], ['class' => '']);
                $html .= $OUTPUT->render_chart($barChart, false);
                $questioncount++;
            }
        }
    }
    $html .= html_writer::end_div();

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

function get_bar_chart_labels($calculatedinterpretationdata) {
    $charlabels = $calculatedinterpretationdata['pieChartLabels'];
    $html = html_writer::start_div('pie-chart-labels-container d-flex align-items-center justify-content-center');
        $html .= html_writer::start_div('d-flex align-items-center');
            foreach ($charlabels as $key => $value) {
                $html .= html_writer::start_div('pie-chart-labels-section d-flex ');
                    $html .= html_writer::start_div('pie-chart-label-color ' . get_bar_chart_colors($key));
                    $html .= html_writer::end_div();
                    $html .= html_writer::start_div('pie-chart-label-text');
                        $html .= html_writer::tag('span', $value, ['class' => 'pie-chart-label']);
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

function calculate_pie_chart_data_by_question_category($interpretationdata, $questioncategory) {
    // Initialize arrays to store interpretation counts
    $interpretationCounts = [];

    // Loop through interpretationdata to extract and count interpretations
    foreach ($interpretationdata as $data) {
        $responses = json_decode($data->survey_responses, true);
        foreach ($responses as $responseKey => $responseValue) {
            if (is_array($responseValue)) {
                if (isset($responseValue['questionCategorySlug']) && $responseValue['questionCategorySlug'] == $questioncategory) {
                    $interpretation = $responseValue['interpretation'];
                    if (!isset($interpretationCounts[$interpretation])) {
                        $interpretationCounts[$interpretation] = 0;
                    }
                    $interpretationCounts[$interpretation]++;
                }
            }
        }
    }

    // Calculate the total number of interpretations
    $totalResponses = array_sum($interpretationCounts);

    // Calculate percentages for each interpretation
    $pieChartData = [];
    $pieChartLabels = [];
    foreach ($interpretationCounts as $interpretation => $count) {
        $percentage = ($count / $totalResponses) * 100;
        $pieChartData[] = round($percentage, 2);
        $pieChartLabels[] = $interpretation;
    }

    return [
        'pieChartData' => $pieChartData,
        'pieChartLabels' => $pieChartLabels
    ];
}

function calculate_bar_chart_data_by_question_category($responseValue, $questioncategory) {
    // Initialize array to store counts of each option
    $optionCounts = [];
    $maxRange = 0;

    if (is_array($responseValue)) {
        if (isset($responseValue['questionCategorySlug']) && $responseValue['questionCategorySlug'] == $questioncategory) {
            $options = $responseValue['options'];
            $question = $responseValue['question'];
            foreach ($options as $option) {
                $optionText = $option['optionText'];
                if (!isset($optionCounts[$optionText])) {
                    $optionCounts[$optionText] = 0;
                }
                if ($responseValue['answer'] === $optionText) {
                    $optionCounts[$optionText]++;
                }
            }
        }
    }
    // Calculate the appropriate max range for the bar chart
    $maxRange = ceil($maxRange / 5) * 5; // Round up to the nearest multiple of 5 for better visualization

    // Separate labels and data
    $barChartLabels = array_keys($optionCounts);
    $barChartData = array_values($optionCounts);

    return [
        'barChartLabels' => $barChartLabels,
        'barChartData' => $barChartData,
        'maxRange' => $maxRange
    ];
}
