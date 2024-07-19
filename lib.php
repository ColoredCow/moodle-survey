<?php
defined('MOODLE_INTERNAL') || die();

function local_saishiko_survey_extend_navigation(global_navigation $nav) {
    global $PAGE;

    $PAGE->requires->css(new moodle_url('/local/moodle_survey/styles.css'));
}