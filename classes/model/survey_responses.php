<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class survey_responses {
    public static function create_survey_responses($record) {
        global $DB;
        $record->submitted_at = date('Y-m-d H:i:s');
        $record->created_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_survey_responses', $record);
    }

    public static function get_survey_response_by_id($id) {
        global $DB;
        return $DB->get_record('cc_survey_responses', ['id' => $id], '*', MUST_EXIST);
    }
}
