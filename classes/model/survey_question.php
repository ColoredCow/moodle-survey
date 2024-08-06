<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();

class survey_question {
    public static function create_survey_question($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_survey_questions', $record);
    }

    public static function update_survey_question($data) {
        global $DB;
        $data->updated_at = date('Y-m-d H:i:s');
        return $DB->update_record('cc_survey_questions', $data);
    }

    public static function delete_survey_question($id) {
        global $DB;
        return $DB->delete_records('cc_survey_questions', ['id' => $id]);
    }

    public static function get_survey_questions_by_survey_id($surveyid) {
        global $DB;
        $surveyquestiondbhelper = new \local_moodle_survey\model\survey_question_option();
    
        $surveyquestionids = $DB->get_records('cc_survey_questions', ['survey_id' => $surveyid]);
    
        $surveyquestions = [];
        $surveyquestioncategories = [];
    
        foreach ($surveyquestionids as $surveyquestionid) {
            $question = $DB->get_record('cc_questions', ['id' => $surveyquestionid->question_id]);
            $category = $DB->get_record('cc_categories', ['id' => $surveyquestionid->question_category_id]);
            $options = $surveyquestiondbhelper->get_survey_question_options_by_survey_question_id($surveyquestionid->question_id);
    
            // Ensure category is not already in the array
            if (!isset($surveyquestioncategories[$category->id])) {
                $surveyquestioncategories[$category->id] = $category;
            }
    
            $surveyquestions[] = [
                'question' => $question,
                'category' => $category,
                'options' => $options
            ];
        }
    
        // Return the questions and categories
        return [
            'surveyquestions' => $surveyquestions,
            'surveyquestioncategories' => $surveyquestioncategories,
        ];
    }
}
