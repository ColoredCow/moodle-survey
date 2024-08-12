<?php
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $addiconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
    $dbhelper = new \local_moodle_survey\model\survey();
    $surveyquestiondbhelper = new \local_moodle_survey\model\survey_question();
    $questioncategories = $dbhelper->get_all_question_categories();
    $surveyquestions = $surveyquestiondbhelper->get_survey_questions_by_survey_id($survey->id);
?>

<div id="question-template" class="d-none">
    <div id="accordion-__INDEX__" class="accordion mb-5 active">
        <div class="accordion-header accordion-header-section">
            <img src="<?php echo $iconurl ?>" alt="Icon" class="accordion-icon">
            <h5>Question <span class="question-number">__POSITION__</span></h5>
        </div>
        <div class="accordion-body pl-5">
            <div class="pl-1 mb-3">
                <div class="row">
                    <div class="col-auto pt-1">
                        <label for="question__INDEX__" class="col-form-label">Question</label>
                    </div>
                    <div class="col-7">
                        <input name="question[__INDEX__][text]" type="text" id="question-__INDEX__" class="form-control" required>
                        <div class="invalid-feedback">
                            - Please provide a valid input.
                        </div>
                    </div>
                </div>
            </div>
            <div class="pl-1 mb-2">
                <div class="row">
                    <div class="col-auto">
                        <label for="question-category-__INDEX__" class="col-form-label">Question Category</label>
                    </div>
                    <div class="col">
                        <select name="question[__INDEX__][category_id]" type="text" id="question-category-__INDEX__" class="custom-select" required>
                            <?php
                                foreach ($questioncategories as $category) {
                                    echo '<option value="'. $category->id . '">' . $category->label . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="pl-1 mb-3" id="option-container-__INDEX__">
                <div class="row question-option">
                    <div class="col-2">
                        <label for="question-score-__INDEX__" class="col-form-label">Score</label>
                        <input name="question[__INDEX__][options][0][score]" type="number" value="0" id="question-score-__INDEX__" class="form-control" required>
                        <div class="invalid-feedback">
                            - Please provide a valid input.
                        </div>
                    </div>
                    <div class="col-6">
                        <label for="question-option-__INDEX__" class="col-form-label">Associated Option</label>
                        <input name="question[__INDEX__][options][0][option]" type="number" value="0" id="question-option-__INDEX__" class="form-control" required>
                        <div class="invalid-feedback">
                            - Please provide a valid input.
                        </div>
                    </div>
                </div>
            </div>
            <div class="pl-1 mb-3">
                <button type="button" class="add-new-button add-new-question-option-button" data-id="__INDEX__"><img src="<?php echo $addiconurl ?>" alt="Icon" class="plus-icon">Add new score and associate option</button>
            </div>
        </div>
    </div>
</div>

<div id="question-option-template" class="d-none">
    <div class="row question-option">
        <div class="col-2">
            <label for="question-score-__INDEX__" class="col-form-label">Score</label>
            <input name="question[__INDEX__][options][__OPTIONINDEX__][score]" type="number" value="0" id="question-score-__INDEX__" class="form-control" required>
            <div class="invalid-feedback">
                - Please provide a valid input.
            </div>
        </div>
        <div class="col-6">
            <label for="question-option-__INDEX__" class="col-form-label">Associated Option</label>
            <input name="question[__INDEX__][options][__OPTIONINDEX__][option]" type="text" id="question-option-__INDEX__" class="form-control" required>
            <div class="invalid-feedback">
                - Please provide a valid input.
            </div>
        </div>
    </div>
</div>

<form method="POST" class="needs-validation" novalidate>
    <div id="question-item-section" class="question-item-section">
        <?php 
            foreach ($surveyquestions['surveyquestions'] as $index => $question) {
                echo ' 
                <div id="accordion-'. $index .'" class="accordion mb-5 active">
                    <div class="accordion-header accordion-header-section">
                        <img src="' . $iconurl . '" alt="Icon" class="accordion-icon">
                        <h5>Question <span class="question-number">' . $index + 1 . '</span></h5>
                    </div>
                    <div class="accordion-body pl-5">
                        <div class="pl-1 mb-3">
                            <div class="row">
                                <div class="col-auto pt-1">
                                    <label for="question' . $index . '" class="col-form-label">Question</label>
                                </div>
                                <div class="col-7">
                                    <input class="d-none" name="tab" value="questions" required/>  
                                    <input name="question[' . $index . '][id]" class="d-none" value="' . $question['question']->id . '" required/>
                                    <input name="question[' . $index . '][text]" type="text" id="question' . $index . '" class="form-control" value="' . $question['question']->text . '" required/>
                                    <div class="invalid-feedback">
                                        - Please provide a valid input.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pl-1 mb-2">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="questionCategory' . $index . '" class="col-form-label">Question Category</label>
                                </div>
                                <div class="col">
                                    <select name="question[' . $index . '][category_id]" id="questionCategory' . $index . '" class="custom-select" required>';
                                        foreach ($questioncategories as $category) {
                                            echo '<option value="' . $category->id . '" '. ($question['category']->id == $category->id ? 'selected' : '') .'>' . $category->label . '</option>';
                                        }
                                    echo '</select>
                                </div>
                            </div>
                        </div>
                        <div class="pl-1 mb-3" id="option-container-' . $index . '">';
                        $optionindex = 0;
                        foreach ($question['options'] as $option) {
                            echo '<div class="row question-option">
                                    <div class="col-2">
                                        <label for="score' . $optionindex . '" class="col-form-label">Score</label>
                                        <input name="question[' . $index . '][options][' . $optionindex . '][id]" class="d-none" value="' . $option->id . '" required>
                                        <input name="question[' . $index . '][options][' . $optionindex . '][score]" type="number" value="' . $option->score . '" id="score' . $optionindex . '" class="form-control" required>
                                        <div class="invalid-feedback">
                                            - Please provide a valid input.
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label for="associatedOption' . $optionindex . '" class="col-form-label">Associated Option</label>
                                        <input name="question[' . $index . '][options][' . $optionindex . '][option]" type="text" value="' . $option->option_text . '" id="associatedOption' . $optionindex . '" class="form-control" required>
                                        <div class="invalid-feedback">
                                            - Please provide a valid input.
                                        </div>
                                    </div>
                                </div>';
                            $optionindex++;
                        }
                        echo '</div><div class="pl-1 mb-3">
                            <button type="button" class="add-new-button add-new-question-option-button" data-id="' . $index . '"><img src="' . $addiconurl . '" alt="Icon" class="plus-icon">Add new score and associate option</button>
                        </div>
                    </div>
                </div>';
            }
        ?>
    </div>

    <button type="button" id="add-new-question-button" class="add-new-button"><img src="<?php echo $addiconurl ?>" alt="Icon" class="plus-icon">Add new question</button>
    <div class="custom-form-action-buttons">
        <button name="pressed_button" value="cancel" type="submit" class="custom-question-form-cancel-button">Cancel</button>
        <button name="pressed_button" value="save" type="submit" class="custom-question-form-submit-button ml-4">Save & Continue</button>
    </div>
</form>