<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class survey_question_option {
    public static function create_survey_question_options($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_survey_question_options', $record);
    }

    public static function update_survey_question_options($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_survey_question_options', $data);
    }

    public static function delete_survey_question_options($id) {
        global $DB;
        return $DB->delete_records('cc_survey_question_options', ['id' => $id]);
    }

    public static function get_survey_question_options_by_survey_question_id($surveyquestionid) {
        global $DB;
        $sql = "SELECT * FROM {cc_survey_question_options} WHERE survey_question_id = :surveyquestionid";
        $params = ['surveyquestionid' => $surveyquestionid];
        return $DB->get_records_sql($sql, $params);
    }

    public static function get_options_by_option_text($optiontext) {
        global $DB;
        $sql = "SELECT * FROM {cc_survey_question_options} WHERE option_text = :optiontext";
        $params = ['optiontext' => $optiontext];
        return $DB->get_record_sql($sql, $params);
    }
    
    public static function get_option_ids_for_survey($surveyid) {
        global $DB;
        $sql = "SELECT option.id FROM {cc_survey_questions} as survey_question
            JOIN {cc_survey_question_options} as option ON option.survey_question_id = survey_question.id
            WHERE survey_question.survey_id = :surveyid
        ";
        $params = ['surveyid' => $surveyid];
        return $DB->get_fieldset_sql($sql, $params);
    }

    public static function delete_list_of_question_options($optionids) {
        global $DB;
        $DB->delete_records_list('cc_survey_question_options', 'id', $optionids);
    }
}
