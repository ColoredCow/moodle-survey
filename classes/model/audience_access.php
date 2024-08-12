<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class audience_access {
    public static function create_audience_access($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_survey_audience_access', $record);
    }

    public static function update_audience_access($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_survey_audience_access', $data);
    }

    public static function get_audience_acccess_by_survey_id($surveyid) {
        global $DB;
        $sql = "SELECT * FROM {cc_survey_audience_access} WHERE survey_id = :survey_id";
        $params = ['survey_id' => $surveyid];
        return $DB->get_record_sql($sql, $params);
    }

    public static function get_schools() {
        global $DB;
        return $DB->get_records('company');
    }

}
