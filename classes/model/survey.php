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

    public static function get_category_by_id($id) {
        global $DB;
        return $DB->get_record('cc_categories', ['id' => $id], '*', MUST_EXIST);
    }
    
    public static function get_all_question_categories() {
        global $DB;
        return $DB->get_records('cc_categories', array('type' => 'question'));
    }

    public static function get_question_categories_for_survey($surveyid) {
        global $DB;
        $sql = "SELECT 
            c.id, 
            c.label, 
            cci.interpreted_as, 
            cci.score_from, 
            cci.score_to
        FROM {cc_survey_questions} sq
        JOIN {cc_categories} c ON sq.question_category_id = c.id
        LEFT JOIN {cc_question_category_interpretations} cci 
            ON sq.question_category_id = cci.question_category_id 
            AND sq.survey_id = cci.survey_id
        WHERE sq.survey_id = :survey_id
        GROUP BY 
            c.id, 
            c.label, 
            cci.interpreted_as, 
            cci.score_from, 
            cci.score_to";
        
        $params = ['survey_id' => $surveyid];
        $categories = $DB->get_records_sql($sql, $params);

        // // Display the results
        // $results = [];
        // foreach ($categories as $category) {
        //     $results[] = ['id' => $category->id, 'label' => $category->label];
        // }

        return $categories;
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

    public static function get_survey_data($surveyId) {
        global $DB;
        $sql = "SELECT sq.*, q.text AS question_text, q.type AS question_type, 
                GROUP_CONCAT(o.option_text SEPARATOR ', ') AS option_texts,
                GROUP_CONCAT(o.option_text SEPARATOR ', ') AS option_texts
            FROM {cc_survey_questions} sq
            JOIN {cc_questions} q ON sq.question_id = q.id
            LEFT JOIN {cc_survey_question_options} o ON sq.id = o.survey_question_id
            LEFT JOIN {cc_question_category_interpretations} cci ON q.id = cci.survey_id
            WHERE sq.survey_id = :surveyid
            GROUP BY sq.id, q.text, q.type, cci.id";
        $params = ['surveyid' => $surveyId];
        $results = $DB->get_records_sql($sql, $params);

        $response = [
            "surveyData" => [
                "interpretations" => []
            ]
        ];

        foreach ($results as $record) {
            if ($record->question_id) {
                $questionId = $record->question_id;

                if (!isset($response[$questionId])) {
                    $questioncategory = self::get_category_by_id($record->question_category_id);
                    $response[$questionId] = [
                        "questionId" => $record->question_id,
                        "question" => $record->question_text,
                        "questionCategory" => $questioncategory->label,
                        "questionCategorySlug" => $questioncategory->slug,
                        "type" => $record->question_type,
                        "answer" => '',
                        "score" => (int)$record->score,
                        "position" => (int)$record->question_position,
                        "interpretation" => $record->interpreted_as,
                        "options" => []
                    ];
                }
                if ($record->option_texts) {
                    $options = explode(',', $record->option_texts);
                    foreach ($options as $option) {
                        $response[$questionId]['options'][] = [
                            "optionText" => trim($option),
                            "scoreFrom" => (int)$record->score_from,
                            "scoreTo" => (int)$record->score_to
                        ];
                    }
                }

                $response['surveyData']['interpretations'][] = [
                    $questioncategory->slug = [
                        "text"  => $record->interpreted_as,
                        "range" => [(int)$record->score_from, (int)$record->score_to]
                    ]
                ];
            }
        }
        return $response;
    }

    public static function get_all_survey_status() {
        global $DB;
    
        $sql = "SELECT DISTINCT status FROM {cc_surveys}";
        $records = $DB->get_records_sql($sql);
    
        $statusoptions = [
            'all' => 'Select Status'
        ];
    
        foreach ($records as $record) {
            $status = $record->status;
            $statusoptions[$status] = $status;
        }
    
        return $statusoptions;
    }

    public static function get_survey_status($survey) {
        $currentDate = date('Y-m-d');
        $statuses = get_string('published', 'local_moodle_survey');
        $startDate = $survey->start_date;
        $endDate = $survey->end_date;
        if($survey->status == get_string('draft', 'local_moodle_survey')) {
            return $statuses = get_string('draft', 'local_moodle_survey');
        }

        if ($currentDate >= $startDate && $currentDate <= $endDate) {
            $statuses = get_string('live', 'local_moodle_survey');
        } elseif ($currentDate > $endDate) {
            $statuses = get_string('completed', 'local_moodle_survey');
        }
    
        return $statuses;
    }

    public static function get_filling_survey_insights($surveyid) {
        global $DB;
        $sql = "SELECT
                    sq.question_id,
                    sq.survey_id,
                    sq.question_category_id,
                    c.label,
                    c.slug,
                    sqo.score,
                    qci.score_from,
                    qci.score_to,
                    qci.interpreted_as
                FROM
                    {cc_survey_questions} sq
                JOIN
                    {cc_survey_question_options} sqo
                ON
                    sq.question_id = sqo.survey_question_id
                JOIN
                    {cc_categories} c
                ON
                    sq.question_category_id = c.id
                JOIN
                    {cc_question_category_interpretations} qci
                ON
                    sq.question_category_id = qci.question_category_id
                    AND sq.survey_id = qci.survey_id
                WHERE
                    sq.survey_id = :surveyid";
        $params = ['surveyid' => $surveyid];
        $results = $DB->get_records_sql($sql, $params);

        return $results;
    }
}
