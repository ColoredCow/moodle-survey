<?php
    $iconurl = new \moodle_url('/local/moodle_survey/pix/arrow-down.svg');
    $addiconurl = new \moodle_url('/local/moodle_survey/pix/plus-icon.svg');
    $dbhelper = new \local_moodle_survey\model\survey();
    $questioncategories = $dbhelper->get_question_categories_for_survey($survey->id);
    $isquestioncategoriesdata = sizeof($questioncategories) <= 0;
    if($ispagetypecreate) {
        echo html_writer::tag('div', get_string('fillgeneraldetailsform', 'local_moodle_survey'), ['class' => 'alert alert-info']);
        return;
    } else if($isquestioncategoriesdata) {
        echo html_writer::tag('div', get_string('noquestioncategorychooses', 'local_moodle_survey'), ['class' => 'alert alert-info']);
        return;
    }
?>

<div id="interpretation-template" class="d-none">
    <div class="mb-3" id="interpretation-__INDEX__-__INTERPRETATIONINDEX__">
        <div class="row col-8 pl-1 question-interpretation">
            <div class="col-2">
                <label for="from-__INTERPRETATIONINDEX__" class="col-form-label">From</label>
                <input name="interpretation[__INDEX__][interpretations][__INTERPRETATIONINDEX__][from]" type="number" value="0" id="from-__INTERPRETATIONINDEX__" class="form-control" required>
                <div class="invalid-feedback">
                    - Invalid input.
                </div>
            </div>
            <div class="col-2">
                <label for="to-__INTERPRETATIONINDEX__" class="col-form-label">To</label>
                <input name="interpretation[__INDEX__][interpretations][__INTERPRETATIONINDEX__][to]" type="number" value="0" id="to-__INTERPRETATIONINDEX__" class="form-control" required>
                <div class="invalid-feedback">
                    - Invalid input.
                </div>
            </div>
            <div class="col-8">
                <label for="interpreted-as-__INTERPRETATIONINDEX__" class="col-form-label">Interpreted as</label>
                <input name="interpretation[__INDEX__][interpretations][__INTERPRETATIONINDEX__][interpreted_as]" type="text" value="" id="interpreted-as-__INTERPRETATIONINDEX__" class="form-control" required>
                <div class="invalid-feedback">
                    - Please provide a valid input.
                </div>
            </div>
        </div>
    </div>
</div>


<form method="POST" class="needs-validation" novalidate>
    <div id="survey-interpretation-container">
        <input class="d-none" name="tab" value="interpretations" required/>  
        <?php 
            $index = 0;
            foreach ($questioncategories as $category) {
                echo ' 
                <div id="accordion-'. $index .'" class="accordion mb-5 active">
                    <div class="accordion-header accordion-header-section">
                        <img src="' . $iconurl . '" alt="Icon" class="accordion-icon">
                        <h5>Question Category <span class="question-category-number" data-id="' . $index . '">' . $index + 1 . '</span></h5>
                    </div>
                    <div class="accordion-body pl-5">
                        <div class="pl-1 mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <label for="question-category-' . $index . '" class="col-form-label">Question Category</label>
                                </div>
                                <div class="col-4">
                                    <input name="interpretation[' . $index . '][category_id]" class="d-none" value="' . $category->id . '" required>
                                    <input disabled id="question-category-' . $index . '" class="form-control" value="' . $category->label . '" required>
                                    <div class="invalid-feedback">
                                        - Please provide a valid input.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="interpretation-container-' . $index . '" class="mb-3">
                        ';

                        $interpretationindex = 0;
                        $interpretations = $dbhelper->get_interpretation_for_survey_category($survey->id, $category->id);
                        foreach ($interpretations as $interpretation) {
                            echo '<div class="mb-3" id="interpretation-' . $index . '-' . $interpretationindex . '">
                                    <div class="row col-8 pl-1 question-interpretation">
                                        <div class="col-2">
                                            <label for="from-' . $interpretationindex . '" class="col-form-label">From</label>
                                            <input name="interpretation[' . $index . '][interpretations][' . $interpretationindex . '][id]" class="d-none" value="' . $interpretation->id . '" required>
                                            <input name="interpretation[' . $index . '][interpretations][' . $interpretationindex . '][from]" type="number" value="' . $interpretation->score_from . '" id="from-' . $interpretationindex . '" class="form-control" required>
                                            <div class="invalid-feedback">
                                                - Invalid input.
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <label for="to-' . $interpretationindex . '" class="col-form-label">To</label>
                                            <input name="interpretation[' . $index . '][interpretations][' . $interpretationindex . '][to]" type="number" value="' . $interpretation->score_to . '" id="to-' . $interpretationindex . '" class="form-control" required>
                                            <div class="invalid-feedback">
                                                - Invalid input.
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <label for="interpreted-as-' . $interpretationindex . '" class="col-form-label">Interpreted as</label>
                                            <input name="interpretation[' . $index . '][interpretations][' . $interpretationindex . '][interpreted_as]" type="text" value="' . $interpretation->interpreted_as . '" id="interpreted-as-' . $interpretationindex . '" class="form-control" required>
                                            <div class="invalid-feedback">
                                                - Please provide a valid input.
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                            $interpretationindex++;
                        }
                        echo '</div>
                        <div class="pl-1 mb-3">
                            <button type="button" id="add-new-interpretation-button-' . $index . '" class="add-new-button add-new-interpretation-button" data-id="' . $index . '"><img src="' . $addiconurl . '" alt="Icon" class="plus-icon">Add new score and associate option</button>
                        </div>
                    </div>
                </div>';
                $index++;
            }
        ?>
    </div>

    <div class="custom-form-action-buttons">
        <button name="pressed_button" value="cancel" type="submit" class="custom-question-form-cancel-button">Cancel</button>
        <button name="pressed_button" value="save" type="submit" class="custom-question-form-submit-button ml-4">Save & Continue</button>
    </div>
</form>