<?php
defined('MOODLE_INTERNAL') || die();

function local_moodle_survey_extend_navigation(global_navigation $nav) {
    global $PAGE;
    $PAGE->requires->css(new moodle_url('/local/moodle_survey/css/styles.css'));
}

function local_moodle_survey_before_http_headers() {
    global $CFG;
    $currenturl = $_SERVER['REQUEST_URI'];
    if (strpos($currenturl, '/blocks/iomad_company_admin/index.php') !== false) {
        redirect($CFG->wwwroot . '/theme/academi/moodle_school/manage_school.php');
    }
}

function is_sel_admin() {
    return is_siteadmin();
}

function is_counsellor() {
    global $USER, $DB;
    $systemcontextid = \context_system::instance()->id;
    $role = $DB->get_record('role', ['shortname' => 'counsellor'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id, $systemcontextid);
}

function is_teacher() {
    global $USER, $DB;
    $systemcontextid = \context_system::instance()->id;
    $role = $DB->get_record('role', ['shortname' => 'teacher'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id, $systemcontextid);
}

function is_school_admin() {
    global $USER, $DB;
    $systemcontextid = \context_system::instance()->id;
    $role = $DB->get_record('role', ['shortname' => 'schooladmin'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id, $systemcontextid);
}

function is_principal() {
    global $USER, $DB;
    $systemcontextid = \context_system::instance()->id;
    $role = $DB->get_record('role', ['shortname' => 'principal'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id, $systemcontextid);
}

function is_student() {
    global $USER, $DB;
    $systemcontextid = \context_system::instance()->id;
    $role = $DB->get_record('role', ['shortname' => 'student'], 'id', MUST_EXIST);
    return user_has_role_assignment($USER->id, $role->id, $systemcontextid);
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

function get_user_grade() {
    global $DB, $USER;
    return $DB->get_record('cc_user_grade', ['user_id' => $USER->id]);
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