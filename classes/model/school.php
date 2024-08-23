<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/moodle_survey/lib.php');

class school {
    public static function get_enrolled_students_count() {
        global $DB;
        if (is_sel_admin()) {
            $sql = "SELECT COUNT(id) FROM {role_assignments} WHERE roleid = (SELECT id FROM {role} WHERE shortname = 'student')";
            return $DB->count_records_sql($sql);
        }

        $userschool = get_user_school();
        $sqlquery = "SELECT userid FROM {company_users} where companyid = :companyid";
        $schoolusersids = $DB->get_fieldset_sql($sqlquery, ['companyid' => $userschool->companyid]);
        
        $sql = "SELECT count(id) FROM {role_assignments} WHERE roleid = (SELECT id FROM {role} WHERE shortname = 'student')";
        $params = [];
        if (!empty($schoolusersids)) {
            list($in_sql, $params) = $DB->get_in_or_equal($schoolusersids, SQL_PARAMS_NAMED, 'userid');
            $sql .= " and userid $in_sql";
        }

        return $DB->count_records_sql($sql, $params);
    }

    public static function get_enrolled_teachers_count() {
        global $DB;
        if (is_sel_admin()) {
            $sql = "SELECT COUNT(id) FROM {role_assignments} WHERE roleid = (SELECT id FROM {role} WHERE shortname = 'teacher')";
            return $DB->count_records_sql($sql);
        }

        $userschool = get_user_school();

        $sqlquery = "SELECT userid FROM {company_users} where companyid = :companyid";
        $schoolusersids = $DB->get_fieldset_sql($sqlquery, ['companyid' => $userschool->companyid]);
        $sql = "SELECT count(id) FROM {role_assignments} WHERE roleid = (SELECT id FROM {role} WHERE shortname = 'teacher')";
        $params = [];
        if (!empty($schoolusersids)) {
            list($in_sql, $params) = $DB->get_in_or_equal($schoolusersids, SQL_PARAMS_NAMED, 'userid');
            $sql .= " and userid $in_sql";
        }

        return $DB->count_records_sql($sql, $params);
    }

    public static function get_courses_count() {
        global $DB;
        if (is_sel_admin()) {
            $coursecategory = $DB->get_record('course_categories', ['parent' => 0, 'name' => "Courses"]);
            $params = [
                'coursecategoryid' =>  $coursecategory->id
            ];
            $sql = "SELECT count(*) FROM {course} where category in (SELECT id from {course_categories} where parent = :coursecategoryid)";
            return $DB->count_records_sql($sql, $params);
        }

        $userschool = get_user_school();
        $schoolcategory = $DB->get_record('course_categories', ['parent' => 0, 'name' => $userschool->name]);
        $sql = "SELECT * FROM {course} where category in (SELECT id from {course_categories} where parent = :schoolcategoryid and name = 'Courses')";

        $coursecategory = $DB->get_records_sql($sql, ['schoolcategoryid' => $schoolcategory->id]);
        if (empty($coursecategory)) {
            return 0;
        }

        $params = [
            'parentcategoryid' => $coursecategory['id']
        ];
        $sql = "SELECT count(*) FROM {course} where category in (SELECT id from {course_categories} where parent = :parentcategoryid)";
        return $DB->count_records_sql($sql, $params);
    }

    public function get_school_by_id($id) {
        global $DB;
        return $DB->get_record('company', ['id' => $id], '*', MUST_EXIST);
    }
}
