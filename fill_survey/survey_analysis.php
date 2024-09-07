<?php

require_once('../../../config.php');
require_once($CFG->dirroot . '/local/moodle_survey/lib.php');
require_login();
if (!has_capability('local/moodle_survey:view-survey-analysis', \context_system::instance())) {
    redirect(new moodle_url('/'));
}
echo $OUTPUT->header();

use core\chart_pie;
use core\chart_series;
use core\chart_bar;

initialize_page();
$id = required_param('id', PARAM_INT);
$surveydbhelper = $dbhelper = new \local_moodle_survey\model\survey();
$survey = $surveydbhelper->get_survey_by_id($id);

$statusoptions = get_string('surveyinsighttypes', 'local_moodle_survey');
$questioncategories = get_question_categories($surveydbhelper, $id);
$currentinsighttype = optional_param('insighttype', 'teacher', PARAM_ALPHA);
$questioncategory = optional_param('category', array_key_first($questioncategories), PARAM_ALPHANUMEXT);
$interpretationdata = $surveydbhelper->get_interpretations_data_by_survey_id_and_question_category_id($id, $currentinsighttype);

$url = new moodle_url('/local/moodle_survey/fill_survey/survey_analysis.php', ['id' => $id]);
$downarrowiconurl = new moodle_url('/local/moodle_survey/pix/arrow-down.svg');

echo render_survey_analysis_title($id, $url, $currentinsighttype, $questioncategory, $statusoptions, $survey);
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

function render_survey_analysis_title($id, $url, $currentinsighttype, $questioncategory, $statusoptions, $survey) {
    $html = html_writer::start_tag('form', ['method' => 'get', 'action' => $url, 'id' => 'filter-form']);
    $html .= html_writer::start_tag('div', ['class' => 'survey-analysis-title d-flex justify-content-between']);
        $html .= html_writer::tag('h3', 'Surveys/' . $survey->name, ['class' => 'survey-analysis-heading']);
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
    
    $CFG->chart_colorset = ['#F16824'];
    $html .= html_writer::start_div('horizontal-chart');
    $questioncount = 1;

    foreach ($interpretationdata as $data) {
        $responses = json_decode($data->survey_responses, true);
        
        foreach ($responses as $responseValue) {
            $calculateddata = calculate_bar_chart_data_by_question_category($responseValue, $questioncategory);
        }
    }

    foreach ($calculateddata as $questionId => $questionData) {
        $barChart = new chart_bar();
        $barChart->set_legend_options(['display' => false]);
        $barChart->set_horizontal(true);

        // Prepare data and labels for the chart
        $barChartLabels = array_keys($questionData['labels']);
        $barChartData = array_values($questionData['labels']);

        $series = new chart_series('', $barChartData);
        $barChart->add_series($series);
        $barChart->set_labels($barChartLabels);

        $html .= html_writer::tag('h4', 'Q' . $questioncount .': ' . $questionData['question'], ['class' => '']);
        $html .= $OUTPUT->render_chart($barChart, false);
        $questioncount++;
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

function get_question_categories($surveydbhelper, $id) {
    $questioncategories = $surveydbhelper->get_question_categories_by_survey_id($id);
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
                $html .= html_writer::start_div('pie-chart-labels-section d-flex align-items-center');
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
        foreach ($responses['surveyData']['interpretations'] as $responseGroup) {
            foreach ($responseGroup as $category => $response) {
                if (is_array($response)) {
                    if (isset($response['catgororySlug']) && $response['catgororySlug'] == $questioncategory) {
                        $interpretation = $response['text'];
                        // Check if the interpretation exists in interpretationCounts and increment or initialize
                        if (isset($interpretationCounts[$interpretation])) {
                            $interpretationCounts[$interpretation]++;
                        } else {
                            $interpretationCounts[$interpretation] = 1;
                        }
                    }
                }
            }
        }
    }

    $pieChartData = [];
    $pieChartLabels = [];

    foreach ($interpretationCounts as $interpretation => $count) {
        $percentage = ($count / array_sum($interpretationCounts)) * 100;
        $pieChartData[] = number_format($percentage, 1);
        $pieChartLabels[] = $interpretation;
    }

    return [
        'pieChartData' => $pieChartData,
        'pieChartLabels' => $pieChartLabels
    ];
}

function calculate_bar_chart_data_by_question_category($responseValue, $questioncategory) {
    // Initialize array to store counts of each option per question
    static $optionCounts = [];

    if (is_array($responseValue)) {
        if (isset($responseValue['questionCategorySlug']) && $responseValue['questionCategorySlug'] == $questioncategory) {
            $options = $responseValue['options'];
            $questionId = $responseValue['questionId'];

            // Initialize the question ID array if it doesn't exist
            if (!isset($optionCounts[$questionId])) {
                $optionCounts[$questionId] = [
                    'question' => $responseValue['question'],
                    'labels' => [],
                    'data' => []
                ];
            }

            // Initialize counts for each option text
            foreach ($options as $option) {
                $optionText = $option['optionText'];
                if (!isset($optionCounts[$questionId]['labels'][$optionText])) {
                    $optionCounts[$questionId]['labels'][$optionText] = 0;
                }
            }

            if (isset($responseValue['answer'])) {
                $answer = $responseValue['answer'];
                if (isset($optionCounts[$questionId]['labels'][$answer])) {
                    $optionCounts[$questionId]['labels'][$answer]++;
                }
            }
        }
    }

    return $optionCounts;
}
