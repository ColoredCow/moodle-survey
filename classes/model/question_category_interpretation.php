<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class question_category_interpretation {
    public static function create_interpretation($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_question_category_interpretations', $record);
    }

    public static function update_interpretation($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_question_category_interpretations', $data);
    }

    public static function delete_survey($id) {
        global $DB;
        return $DB->delete_records('cc_question_category_interpretations', ['id' => $id]);
    }

    public static function get_interpretation_by_survey_id($surveyid) {
        global $DB;
        $sql = "SELECT * FROM {cc_question_category_interpretations} WHERE survey_id = :survey_id";
        $params = ['survey_id' => $surveyid];
        return $DB->get_records_sql($sql, $params);
    }
}
