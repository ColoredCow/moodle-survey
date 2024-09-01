<?php
namespace local_moodle_survey\model;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/moodle_survey/lib.php');

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

    public static function create_categories($record) {
        global $DB;
        $record->created_at = date('Y-m-d H:i:s');
        $record->updated_at = date('Y-m-d H:i:s');
        return $DB->insert_record('cc_categories', $record);
    }
    public static function delete_survey($id) {
        global $DB;
        return $DB->delete_records('cc_surveys', ['id' => $id]);
    }

    public static function delete_categories($id) {
        global $DB;
        return $DB->delete_records('cc_categories', ['id' => $id]);
    }

    public static function get_all_survey_categories() {
        global $DB, $USER;

        if (is_sel_admin()) {
            return $DB->get_records('cc_categories', array('type' => 'survey'));
        }

        $userschool = get_user_school();
        $sql = "SELECT categories.* FROM {cc_survey_audience_access} as survey_school
            JOIN {cc_surveys} as survey ON survey.id = survey_school.survey_id
            JOIN {cc_categories} as categories ON survey.category_id = categories.id
            WHERE survey_school.school_id = :schoolid;
        ";
        $params = ['schoolid' => $userschool->companyid];
        return $DB->get_records_sql($sql, $params);
    }

    public static function get_category_by_id($id) {
        global $DB;
        return $DB->get_record('cc_categories', ['id' => $id], '*', MUST_EXIST);
    }
    
    public static function get_all_question_categories() {
        global $DB;
        return $DB->get_records('cc_categories', array('type' => 'question'));
    }

    public static function get_question_categories_by_survey_id($surveyid) {
        global $DB;
        $sql = "SELECT ca.* FROM {cc_survey_questions} sq LEFT JOIN {cc_categories} ca ON ca.id = sq.question_category_id WHERE sq.survey_id = :surveyid GROUP BY ca.id;";
        $params = ['surveyid' => $surveyid];
        return $DB->get_records_sql($sql, $params);
    }

    public static function get_active_survey_count() {
        global $DB;
        if (is_sel_admin()) {
            return $DB->count_records('cc_surveys', ['status' => 'Live']);
        }
        
        $userschool = get_user_school();
        $sql = "SELECT count(*) FROM {cc_survey_audience_access} as a JOIN {cc_surveys} as b ON a.survey_id = b.id where b.status = 'Live' and a.school_id = :schoolid";
        return $DB->count_records_sql($sql, ['schoolid' => $userschool->companyid]);
    }

    public static function get_categories_by_filters($filters, $categorytype) {
        global $DB;
    
        $sql = "SELECT * FROM {cc_categories} WHERE type = :categorytype";
        $params = ['categorytype'=> $categorytype];
    
        foreach ($filters as $key => $value) {
            switch ($key) {
                case 'search':
                    if (!empty($value)) {
                        $sql .= " AND label LIKE :search";
                        $params['search'] = "%$value%";
                    }
                    continue;

                case 'createdon':
                    if (!empty($value)) {
                        $sql .= " AND DATE(created_at) = :createdon";
                        $params['createdon'] = $value;
                    }
                    continue;
                
                case 'createcategory':
                    if (!empty($value)) {
                        $data = new \stdClass();
                        $data->label = $value;
                        $data->slug = strtolower(str_replace(' ', '-', $value));
                        $data->type = $categorytype;
                        self::create_categories($data);
                    }
                    continue;
                
                case 'categoryid':
                    if (!empty($value)) {
                        self::delete_categories($value);
                    }
            }
        }
    
        return $DB->get_records_sql($sql, $params);
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

    public static function get_interpretation_for_survey_category($surveyid, $surveycategoryid) {
        global $DB;
        return $DB->get_records('cc_question_category_interpretations', ['survey_id' => $surveyid, 'question_category_id' => $surveycategoryid]);
    }

    protected static function get_survey_ids_for_user() {
        global $DB;
    
        // Fetch the user's school information
        $userschool = get_user_school();

        if (!$userschool) {
            return [];
        }
        
        // Define parameters
        $params = [
            'schoolid' => $userschool->companyid,
        ];
    
        $sqlquery = "SELECT survey_id
            FROM {cc_survey_audience_access}
            WHERE school_id = :schoolid";

        if (is_student()) {
            $params['role'] = '"' . get_user_role() . '"';
            $user_grades = json_decode(get_user_grade()->user_grade, true);
            
            $gradeConditions = [];
            foreach ($user_grades as $grade) {
                $gradeConditions[] = "JSON_CONTAINS(student_grade, :grade_$grade)";
                $params["grade_$grade"] = '"' . $grade . '"';
            }
            
            $gradeConditionStr = implode(' OR ', $gradeConditions);
            
            $sqlquery .= " AND JSON_CONTAINS(assigned_to, :role) AND ($gradeConditionStr)";
        } else if (is_teacher()){
            $params['role'] = '"' . get_user_role() . '"';
            $user_grades = json_decode(get_user_grade()->user_grade, true);
            
            $gradeConditions = [];
            foreach ($user_grades as $grade) {
                $gradeConditions[] = "JSON_CONTAINS(teacher_grade, :grade_$grade)";
                $params["grade_$grade"] = '"' . $grade . '"';
            }
            
            $gradeConditionStr = implode(' OR ', $gradeConditions);
            
            $sqlquery .= " AND JSON_CONTAINS(assigned_to, :role) AND ($gradeConditionStr)";
        }

        return $DB->get_fieldset_sql($sqlquery, $params);
    }

    public static function get_surveys($filters) {
        global $DB;
        if (get_user_role() == 'sel_admin') {
            $sql = "SELECT * FROM {cc_surveys} WHERE 1=1";
        } else {
            $survey_ids = self::get_survey_ids_for_user();
            if (empty($survey_ids)) {
                return []; // No surveys available for the user
            }
            list($in_sql, $params) = $DB->get_in_or_equal($survey_ids, SQL_PARAMS_NAMED, 'surveyid');
            $sql = "SELECT * FROM {cc_surveys} WHERE id $in_sql";
        }
    
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

        $sql = "SELECT sq.*, q.text AS question_text, q.type AS question_type, cci.score_from, cci.score_to, cci.interpreted_as, cci.description, GROUP_CONCAT(o.option_text ORDER BY o.id SEPARATOR ', ') AS option_texts, GROUP_CONCAT(o.score ORDER BY o.id SEPARATOR ', ') AS score
            FROM {cc_survey_questions} sq
            LEFT JOIN {cc_questions} q ON sq.question_id = q.id
            LEFT JOIN {cc_survey_question_options} o ON sq.id = o.survey_question_id
            LEFT JOIN {cc_question_category_interpretations} cci ON sq.question_category_id = cci.question_category_id
            WHERE sq.survey_id = :surveyid
            GROUP BY sq.id, q.text, q.type, cci.id, cci.score_from, cci.score_to, cci.interpreted_as, cci.description";

        $params = ['surveyid' => $surveyId];
        $results = $DB->get_records_sql($sql, $params);

        $response = [
            "surveyData" => [
                "categoriesScores" => [],
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
                        "score" => 0,
                        "position" => (int)$record->question_position,
                        "interpretation" => $record->interpreted_as,
                        "options" => []
                    ];
                }
                if ($record->option_texts && $record->score) {
                    $options = explode(',', $record->option_texts);
                    $scores = explode(',', $record->score);
                
                    $numOptions = count($options);
                    $numScores = count($scores);
                
                    if ($numOptions === $numScores) {
                        foreach ($options as $index => $option) {
                            $response[$questionId]['options'][] = [
                                "optionText" => trim($option),
                                "score" => isset($scores[$index]) ? (int) trim($scores[$index]) : 0
                            ];
                        }
                    }
                }

                $newcategoryslug = $questioncategory->slug;
                $newcategorylabel = $questioncategory->label;
                $newinterpretation = [
                    "catgororySlug" => $newcategoryslug,
                    "text" => '',
                    "range" => '',
                    "description" => '',
                ];

                if (!self::interpretation_exists($response['surveyData']['interpretations'], $newcategoryslug, $newinterpretation)) {
                    $response['surveyData']['interpretations'][] = [
                        $newcategorylabel => $newinterpretation
                    ];
                }
            }
        }
        return $response;
    }

    public static function interpretation_exists($surveydatainterpretations, $categoryslug, $newinterpretation) {
        foreach ($surveydatainterpretations as $item) {
            if (isset($item[$categoryslug])) {
                $existingitem = $item[$categoryslug];
                if (
                    $existingitem['catgororySlug'] === $newinterpretation['catgororySlug'] &&
                    $existingitem['text'] === $newinterpretation['text'] &&
                    $existingitem['range'] === $newinterpretation['range'] &&
                    $existingitem['description'] === $newinterpretation['description']
                ) {
                    return true;
                }
            }
        }
        return false;
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

    public static function get_filling_survey_insights($surveyid, $userid) {
        global $DB;
        $sql = "SELECT * FROM {cc_survey_responses} WHERE survey_id = :surveyid AND submitted_by = :userid";
        $params = ['surveyid' => $surveyid, 'userid' => $userid];
        $results = $DB->get_records_sql($sql, $params);

        return $results;
    }

    public static function get_survey_responses_count() {
        global $DB;
        return $DB->count_records_sql("
            SELECT COUNT(DISTINCT survey_id) 
            FROM {cc_survey_responses}
        ");
    }
    
    public static function get_survey_count_by_status($status) {
        global $DB;
        return $DB->count_records('cc_surveys', ['status' => $status]);
    }

    public static function get_survey_count() {
        global $DB;
        if (is_sel_admin()) {
            return $DB->count_records('cc_surveys');
        }
        
        $userschool = get_user_school();
        return $DB->count_records('cc_survey_audience_access', ['school_id' => $userschool->companyid]);
    }

    public static function get_question_category_count() {
        global $DB;
        return $DB->count_records('cc_categories', ['type' => 'question']);
    }

    public static function get_live_surveys_with_interpretations($categoryid, $rolename) {
        global $DB;
        $userschool = get_user_school();
        $sql = "SELECT DISTINCT s.category_id,
                    sr.response AS survey_responses,
                    sr.submitted_by,
                    s.id AS survey_id
                FROM
                    {cc_surveys} s
                    LEFT JOIN {cc_survey_responses} sr ON sr.survey_id = s.id
                    LEFT JOIN {cc_survey_audience_access} sa ON sa.school_id = :schoolid
                    LEFT JOIN {user} u ON sr.submitted_by = u.id
                    LEFT JOIN {role_assignments} ra ON ra.userid = u.id
                    LEFT JOIN {role} r ON ra.roleid = r.id
                WHERE
                    s.category_id = :categoryid
                    AND s.status = :status
                    AND r.shortname = :roleshortname";
        $params = [
            'status' => 'Live',
            'categoryid' => $categoryid,
            'roleshortname' => $rolename,
            'schoolid' => $userschool->companyid
        ];
        
        return $DB->get_recordset_sql($sql, $params);
    }

    public static function get_interpretations_data_by_survey_id_and_question_category_id($surveyid, $rolename) {
        global $DB;
        $sql = "SELECT
                    DISTINCT sr.id,
                    sr.response AS survey_responses,
                    ra.roleid
                FROM
                    {cc_survey_responses} sr
                    LEFT JOIN {role_assignments} ra ON ra.userid = sr.submitted_by
                WHERE
                    sr.survey_id = :surveyid
                    AND ra.roleid = (
                        SELECT
                            id
                        FROM
                            {role}
                        WHERE
                            shortname = :roleshortname
                    )";
    
        $params = [
            'surveyid' => $surveyid,
            'roleshortname' => $rolename
        ];
        
        return $DB->get_records_sql($sql, $params);
    }

    public static function get_survey_responses_count_by_survey_id($surveyid) {
        global $DB;
        return $DB->count_records('cc_survey_responses', ['survey_id' => $surveyid]);
    }

    public function get_category_id_by_slug($categoryslug, $type) {
        global $DB;
        $category = $DB->get_record('cc_categories', ['label' => $categoryslug, 'type' => $type]);
        return $category->id;
    }
}
