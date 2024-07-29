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

    public static function get_surver_category_by_id($id) {
        global $DB;
        return $DB->get_record('cc_categories', ['id' => $id], '*', MUST_EXIST);
    }
    
    public static function get_all_question_categories() {
        global $DB;
        return $DB->get_records('cc_categories', array('type' => 'question'));
    }

    public static function get_surveys($filters) {
        global $DB;
    
        $sql = "SELECT * FROM {cc_surveys} WHERE 1=1";
        $params = [];
    
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'search':
                    if (!empty($value)) {
                        $sql .= " AND name LIKE :search";
                        $params['search'] = "%$value%";
                    }
                    continue;
    
                case 'status':
                    if (!empty($value) && $value !== 'all') {
                        $sql .= " AND status = :status";
                        $params['status'] = $value;
                    }
                    continue;
    
                case 'surveycategory':
                    if (!empty($value) && $value !== 'all') {
                        $sql .= " AND category_id = :surveycategory";
                        $params['surveycategory'] = $value;
                    }
                    continue;

                case 'createdon':
                    if (!empty($value)) {
                        $sql .= " AND DATE(created_at) = :createdon";
                        $params['createdon'] = $value;
                    }
                    continue;
            }
        }
    
        return $DB->get_records_sql($sql, $params);
    }
}
