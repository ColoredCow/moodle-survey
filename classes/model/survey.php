<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class survey {
    public static function get_surveys_by_status($status) {
        global $DB;
        return $DB->get_records('cc_surveys', ['status' => $status]);
    }

    public static function get_survey_by_id($id) {
        global $DB;
        return $DB->get_record('cc_surveys', ['id' => $id], '*', MUST_EXIST);
    }

    public static function create_survey($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_surveys', $record);
    }

    public static function update_survey($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_surveys', $data);
    }

    public static function delete_survey($id) {
        global $DB;
        return $DB->delete_records('cc_surveys', ['id' => $id]);
    }

    public static function get_all_survey_categories() {
        global $DB;
        return $DB->get_records('cc_categories', array('type' => 'survey'));
    }
    
    public static function get_all_question_categories() {
        global $DB;
        return $DB->get_records('cc_categories', array('type' => 'question'));
    }

    public static function get_question_categories_for_survey($surveyid) {
        global $DB;
        $sql = "SELECT c.id AS category_id, c.label AS category_name
            FROM {surveyquestion} sq
            JOIN {questions} q ON sq.question_id = q.id
            JOIN {categories} c ON sq.question_category_id = c.id
            WHERE sq.survey_id = :survey_id
            GROUP BY c.id, c.name";
        
        $params = ['survey_id' => $surveyid];
        $categories = $DB->get_records_sql($sql, $params);


        // Display the results
        $results = [];
        foreach ($categories as $category) {
            $results[] = ['id' => $category->category_id, 'name' => $category->category_name];
        }


        return $results;
    }
}
