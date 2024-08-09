<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class question {
    public static function create_question($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_questions', $record);
    }

    public static function update_question($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_questions', $data);
    }

    public static function delete_question($id) {
        global $DB;
        return $DB->delete_records('cc_questions', ['id' => $id]);
    }

    public static function get_question_by_question_text($questiontext) {
        global $DB;
        $sql = "SELECT * FROM {cc_questions} WHERE text = :questiontext";
        $params = ['questiontext' => $questiontext];
        return $DB->get_record_sql($sql, $params);
    }
}
