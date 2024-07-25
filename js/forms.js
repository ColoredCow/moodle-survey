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

function handleAccordion() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const currentlyActive = document.querySelector('.accordion-item.active');
            
            if (currentlyActive && currentlyActive !== this.parentElement) {
                currentlyActive.classList.remove('active');
                currentlyActive.querySelector('.accordion-body').style.display = 'none';
            }
            
            const isActive = this.parentElement.classList.contains('active');
            this.parentElement.classList.toggle('active', !isActive);
            this.nextElementSibling.style.display = isActive ? 'none' : 'block';
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let questionIndex = 0;

    document.getElementById('add-new-question-button').addEventListener('click', function() {
        questionIndex++;

        const template = document.getElementById('question-template');
        const newQuestion = template.cloneNode(true);

        // Update the IDs and names of the cloned elements
        newQuestion.querySelector('#question-number').textContent = questionIndex + 1;
        newQuestion.setAttribute('data-question-number', questionIndex + 1);

        // Update input names to be unique
        newQuestion.querySelectorAll('input, select').forEach((element) => {
            const name = element.name.replace(/\[\d+\]/, `[${questionIndex}]`);
            element.name = name;
        });

        // Append the new question
        document.getElementById('accordion').appendChild(newQuestion);

        // Re-initialize accordion functionality
        handleAccordion();
    });
});
