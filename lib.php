<?php
defined('MOODLE_INTERNAL') || die();

function local_moodle_survey_extend_navigation(global_navigation $nav) {
    global $PAGE;

    $PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));
}

function is_sel_admin() {
    return is_siteadmin();
}

function is_counsellor() {
    global $USER, $DB;
    $role = $DB->get_record('role', ['shortname' => 'counsellor'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id);
}

function is_teacher() {
    global $USER, $DB;
    $role = $DB->get_record('role', ['shortname' => 'teacher'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id);
}

function is_school_admin() {
    global $USER, $DB;
    $role = $DB->get_record('role', ['shortname' => 'schooladmin'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id);
}

function is_principal() {
    global $USER, $DB;
    $role = $DB->get_record('role', ['shortname' => 'principal'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id);
}

function is_student() {
    global $USER, $DB;
    $role = $DB->get_record('role', ['shortname' => 'student'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id);
}

function get_user_role() {
    global $USER;
    $context = \context_system::instance();
    $roles = get_user_roles($context, $USER->id);

    foreach ($roles as $role) {
        return $role->shortname;
    }

    return 'sel_admin';
}

function get_user_school() {
    global $USER, $DB;
    return $DB->get_record('company_users', ['userid' => $USER->id]);
}

function get_school() {
    global $USER, $DB;
    return $DB->get_record('company', ['id' => get_user_school()->companyid]);
}

function get_user_school_department($schoolshortname=null) {
    global $USER, $DB;

    if (!$schoolshortname) {
        $schoolshortname = get_school()->shortname;
    }

    return $DB->get_record('department', ['shortname' => $schoolshortname]);
}