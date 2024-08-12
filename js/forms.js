const addEventListenerForAccordion = (accordionHeaders) => {
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

const handleAccordion = () => {
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    addEventListenerForAccordion(accordionHeaders)
}

handleAccordion();


document.addEventListener('DOMContentLoaded', function() {
  const addNewQuestionButton = document.getElementById('add-new-question-button');
  const questionContainer = document.getElementById('question-item-section');

  const questionTemplate = document.getElementById('question-template').innerHTML;
  addNewQuestionButton.addEventListener('click', function() {
    let questionNumber = document.querySelectorAll('.question-number').length;
    newQuestionHtml = questionTemplate.replace(/__INDEX__/g, questionNumber-1).replace(/__POSITION__/g, questionNumber)
    questionContainer.insertAdjacentHTML('beforeend', newQuestionHtml);
    
    const addNewQuestionOptionButtons = questionContainer.getElementsByClassName('add-new-question-option-button');
    Array.from(addNewQuestionOptionButtons).forEach(function(button) {
      button.addEventListener('click', addNewQuestionOption);
    });
    const accordion = document.getElementById('accordion-' + (questionNumber-1));
    const accordionHeaders = accordion.querySelectorAll('.accordion-header');

    addEventListenerForAccordion(accordionHeaders)
  });

  const addNewQuestionOptionButtons = document.getElementsByClassName('add-new-question-option-button');
  Array.from(addNewQuestionOptionButtons).forEach(function(button) {
    button.addEventListener('click', addNewQuestionOption);
  });

  let questionsCount = document.querySelectorAll('.question-number').length;
  if (questionsCount == 1) {
    addNewQuestionButton.click();
  }
});

const addNewQuestionOption = (e) => {
  const questionIndex = e.target.getAttribute("data-id")
  const optionContainer = document.getElementById('option-container-' + questionIndex)
  const existingOptionsCount = optionContainer.querySelectorAll('.question-option').length;
  const optionTemplate = document.getElementById('question-option-template').innerHTML;
  newOptionHtml = optionTemplate.replace(/__OPTIONINDEX__/g, existingOptionsCount).replace(/__INDEX__/g, questionIndex)
  optionContainer.insertAdjacentHTML('beforeend', newOptionHtml);
}

function validateForms() {
  'use strict';
  const forms = document.querySelectorAll('.needs-validation');

  forms.forEach(form => {
    form.addEventListener('submit', (event) => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation(); 

      }

      form.classList.add('was-validated');

    });
  });
}

// Disable-Enable Checkbox JS
document.addEventListener('DOMContentLoaded', function() {
  var checkbox = document.getElementById('survey-participation-checkbox');
  var continueButton = document.getElementById('continue-button');

  function toggleButton() {
      var isChecked = checkbox.checked;
      continueButton.classList.toggle('disabled', !isChecked);
      continueButton.setAttribute('aria-disabled', !isChecked);
      continueButton.setAttribute('data-disabled', !isChecked);
      continueButton.style.pointerEvents = isChecked ? 'auto' : 'none';
      continueButton.style.opacity = isChecked ? '1' : '0.5';
  }

  checkbox.addEventListener('change', toggleButton);

  toggleButton();
});

validateForms();