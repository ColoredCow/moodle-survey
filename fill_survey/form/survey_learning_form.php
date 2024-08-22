<?php
    $surveydata = $dbhelper->get_survey_data($id);
    $id = required_param('id', PARAM_INT);
    $dbhelper = new \local_moodle_survey\model\survey();
    $survey = $dbhelper->get_survey_by_id($id);
?>

<form method="POST" class="needs-validation mt-5" novalidate>
    <div id="question-item-section">
        <?php
            $questionindex = 0;
            foreach ($surveydata as $question) {
                if (!$question['question']) {
                    continue;
                }
                $questionindex++;
                $questionoptions = $question['options'];
        ?>
        <div class="mb-4">
            <div class="mb-1 d-flex fill-survey-questions">
                <p class="mb-0"><?php echo $questionindex . '.'; ?></p>&nbsp;&nbsp;
                <label for="question-<?php echo $question['questionId']; ?>" class="form-label d-flex align-items-center mb-0"><?php echo htmlspecialchars($question['question']); ?></label>
            </div>
            <div class="form-check d-flex pl-0 fill-survey-options">
                <?php
                    $optionindex = 0;
                    foreach ($questionoptions as $option) {
                        $optionindex++;
                ?>
                <div class="form-check mr-5">
                    <input class="form-check-input" type="radio" name="question[<?php echo $question['questionId']; ?>]" value="<?php echo htmlspecialchars($option['optionText']); ?>" id="question-<?php echo $question['questionId']; ?>-option-<?php echo $optionindex; ?>" required>
                    <label class="form-check-label" for="question-<?php echo $question['questionId']; ?>-option-<?php echo $optionindex; ?>"><?php echo htmlspecialchars($option['optionText']); ?></label>
                    <div class="invalid-feedback">
                        - Please provide a valid input.
                    </div>
                </div>
                <?php
                    }
                ?>
            </div>
        </div>
        <?php
            }
        ?>
    </div>
    <div class="custom-form-action-buttons">
        <button name="pressed_button" value="cancel" type="submit" class="custom-question-form-cancel-button">Cancel</button>
        <button name="pressed_button" value="save" type="submit" class="custom-question-form-submit-button ml-4">Save & Continue</button>
    </div>
</form>
