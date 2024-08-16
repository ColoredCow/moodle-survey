<?php

require_once('../../../config.php');
require_login();

$context = context_system::instance();

$id = required_param('id', PARAM_INT);
$survey = $DB->get_record('cc_surveys', ['id' => $id], '*', MUST_EXIST);
$PAGE->set_title(get_string('selanalysis', 'local_moodle_survey'));
$PAGE->requires->js(new moodle_url('/local/moodle_survey/js/forms.js'));
echo $OUTPUT->header();
echo render_survey_analysis_title();
echo render_survey_instruction($survey);
echo render_survey_insights();


function render_survey_analysis_title() {
    $html = html_writer::start_tag('div', array('class' => 'survey-analysis-title'));
        $html .= html_writer::tag('h3', 'Surveys/' .  get_string('selanalysis', 'local_moodle_survey') .'', array('class' => 'survey-analysis-heading'));
    $html .= html_writer::end_tag('div');
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


function render_survey_insights() {
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $html = html_writer::start_tag('div', array('class' => '', 'id'=>"accordion"));
        $html .= html_writer::start_tag('div', array('class' => 'accordion active'));
            $html .= html_writer::start_tag('div', array('class' => 'accordion-header accordion-header-section survey-about-container'));
                $html .= html_writer::start_tag('img', array('src' => $iconurl, 'class' => 'accordion-icon'));
                $html .= html_writer::tag('h3', 'Insights', array('class' => 'survey-about-heading mb-0 ml-2'));
            $html .= html_writer::end_tag('div');
            $html .= html_writer::start_tag('div', array('class' => 'accordion-body pl-5'));
                $html .= render_survey_analysis_chart();
            $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
    $html .= html_writer::end_tag('div');
    return $html;
}

function render_survey_analysis_chart() {
    return "here";
}

echo $OUTPUT->footer();
