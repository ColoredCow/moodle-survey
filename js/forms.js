function addQuestionField() {
    // Logic to clone the last question and score fields, update indices, and append to the form
}

document.addEventListener('DOMContentLoaded', function() {
    var accHeaders = document.querySelectorAll('.accordion-header');

    accHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
            var accBody = this.nextElementSibling;
            if (accBody.style.display === 'none') {
                accBody.style.display = 'block';
            } else {
                accBody.style.display = 'none';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('add-category-button').addEventListener('click', function() {
        var container = document.getElementById('new-category-input-container');
        var newSelect = document.createElement('select');
        newSelect.name = 'category';
        newSelect.className = 'form-control category-select';
        newSelect.required = true;
        newSelect.innerHTML = '<option value="0">Inactive</option>' +
                            '<option value="1">Active</option>';
        container.appendChild(newSelect);
    });
});

function createQuestionScoreSection() {
    var newSection = document.createElement('div');
    newSection.className = 'new-option-section question-score-option-section';

    newSection.innerHTML = `
        <div>
            <label for="score" class="form-label">Score</label>
            <input type="number" id="number" class="question-score" name="score[]" min="1" max="10">
        </div>
        <div class="associated-option-section">
            <label for="associatedoption" class="form-label">Associated option</label>
            <input type="text" id="associatedoption" class="question-associatedoption" name="associatedoption[]" placeholder="ex: never">
        </div>
    `;

    return newSection;
}

function createQuestionCategorySelectionSection() {
    var newSection = document.createElement('div');
    newSection.className = 'question-score-category-selection';

    newSection.innerHTML = `
        <select id="id_name" name="status" class="form-control question-score-category-selection" required>
            <option value="0">Inactive</option>
            <option value="1">Active</option>
        </select>
    `;

    return newSection;
}

// set the default value 0 of the input number type 
document.getElementById("number").defaultValue = "0";

document.addEventListener('DOMContentLoaded', function() {
    let questionCounter = 1;

    // Initialize the click event for adding new questions
    document.getElementById('add-new-question-button').addEventListener('click', function() {
        const template = document.getElementById('question-template');
        const newQuestion = template.cloneNode(true);
        const sectionsContainer = newQuestion.querySelector('#new-sections-container');
        if (sectionsContainer) {
            sectionsContainer.innerHTML = ''; // Clear existing sections
        }

        questionCounter++;
        newQuestion.style.display = 'block';
        newQuestion.removeAttribute('id');
        newQuestion.querySelector('.question-number').innerText = questionCounter;

        // Update IDs for new elements
        const newScoreButton = newQuestion.querySelector('#add-new-score-button');
        const newCategoryButton = newQuestion.querySelector('#add-new-category');
        newScoreButton.id = `add-new-score-button-${questionCounter}`;
        newCategoryButton.id = `add-new-category-${questionCounter}`;

        // Add event listeners for newly added buttons
        newScoreButton.addEventListener('click', function() {
            const container = newQuestion.querySelector('#new-sections-container');
            if (container) {
                container.appendChild(createQuestionScoreSection());
            }
        });

        newCategoryButton.addEventListener('click', function() {
            const container = newQuestion.querySelector('#question-category-selection');
            container.appendChild(createQuestionCategorySelectionSection());
        });

        initializeAccordion(newQuestion);

        document.querySelector('#questions .accordion').insertBefore(newQuestion, this);
    });

    // Attach event listeners for the initial template
    const initialScoreButton = document.getElementById('add-new-score-button');
    const initialCategoryButton = document.getElementById('add-new-category');
    
    initialScoreButton.addEventListener('click', function() {
        const container = document.getElementById('new-sections-container');
        if (container) {
            container.appendChild(createQuestionScoreSection());
        }
    });

    initialCategoryButton.addEventListener('click', function() {
        const container = document.getElementById('question-category-selection');
        if (container) {
            container.appendChild(createQuestionCategorySelectionSection());
        }
    });
});

function createQuestionScoreSection() {
    const section = document.createElement('div');
    section.className = 'question-score-section';
    section.innerHTML = `
        <div>
            <label class="form-label">Score</label>
            <input type="number" class="question-score" name="score[]" min="1" max="10">
        </div>
        <div class="associated-option-section">
            <label class="form-label">Associated Option</label>
            <input type="text" class="question-associatedoption" name="associatedoption[]" placeholder="Interpreted as">
        </div>
    `;
    return section;
}

function createQuestionCategorySelectionSection() {
    const section = document.createElement('div');
    section.className = 'question-category-section';
    section.innerHTML = `
        <div class="question-category-selection">
            <label class="form-label">Question Category</label>
            <select name="status" class="form-control question-score-category-selection" required>
                <option value="0">Inactive</option>
                <option value="1">Active</option>
            </select>
        </div>
    `;
    return section;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize click event for 'new-score-and-associated-option' buttons
    document.body.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('add-new-button')) {
            const container = event.target.closest('.accordion-body').querySelector('#new-score-sections-container');
            if (container) {
                container.appendChild(createNewScoreAssociatedOptionSection());
            } else {
                console.error('Container for new score sections not found.');
            }
        }
    });

    let questionCategoryCounter = 1;

    document.getElementById('add-new-interpretation').addEventListener('click', function() {
        const template = document.getElementById('question-category-template');
        const newQuestion = template.cloneNode(true);

        questionCategoryCounter++;
        newQuestion.style.display = 'block';
        newQuestion.removeAttribute('id');
        newQuestion.querySelector('.question-category-number').innerText = questionCategoryCounter;

        // Clear any existing dynamically added sections
        const sectionsContainer = newQuestion.querySelector('#new-score-sections-container');
        if (sectionsContainer) {
            sectionsContainer.innerHTML = ''; // Clear existing sections
        }

        // Give the new button a unique ID
        const newButton = newQuestion.querySelector('.add-new-button');
        newButton.id = 'new-score-and-associated-option-' + questionCategoryCounter;

        // Initialize accordion functionality
        initializeAccordion(newQuestion);

        document.querySelector('#interpretations .accordion').insertBefore(newQuestion, this);
    });

    function createNewScoreAssociatedOptionSection() {
        var newSection = document.createElement('div');
        newSection.className = 'new-option-section question-score-option-section';

        newSection.innerHTML = `
            <div class="question-score-section">
                <div>
                    <label for="from" class="form-label">From</label>
                    <input type="number" class="question-score" id="number" name="scorefrom[]" min="1" max="10">
                </div>
                <div>
                    <label for="to" class="form-label">To</label>
                    <input type="number" class="question-score" id="number" name="scoreto[]" min="1" max="10">
                </div>
                <div>
                    <label for="interpretedas" class="form-label">Interpreted as</label>
                    <input type="text" id="interpretedas" class="question-interpretedas" name="interpretedas[]" placeholder="ex: never">
                </div>
            </div>
        `;

        return newSection;
    }

});

function initializeAccordion(section) {
    const headers = section.querySelectorAll('.accordion-header');
    headers.forEach(header => {
        header.addEventListener('click', function() {
            const body = this.nextElementSibling;
            if (body && body.style.display === 'block') {
                body.style.display = 'none';
            } else {
                document.querySelectorAll('.accordion-body').forEach(el => {
                    el.style.display = 'none';
                });
                if (body) {
                    body.style.display = 'block';
                }
            }
        });
    });
}
