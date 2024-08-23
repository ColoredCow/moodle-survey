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

  document.addEventListener('click', function(event) {
    if (event.target.classList.contains('delete-question-button')) {
        deleteQuestion(event.target);
    }
    if (event.target.classList.contains('delete-option-button')) {
        deleteOption(event.target);
    }
  });


  const addNewQuestionOptionButtons = document.getElementsByClassName('add-new-question-option-button');
  Array.from(addNewQuestionOptionButtons).forEach(function(button) {
    button.addEventListener('click', addNewQuestionOption);
  });
  
  const addNewInterpretationButtons = document.getElementsByClassName('add-new-interpretation-button');
  Array.from(addNewInterpretationButtons).forEach(function(button) {
    button.addEventListener('click', addNewInterpretation);
  });

  let questionsCount = document.querySelectorAll('.question-number').length;
  if (questionsCount == 1) {
    addNewQuestionButton.click();
  }
  
  let categories = document.querySelectorAll('.question-category-number');
  Array.from(categories).forEach(function(categoryElement) {
    const categoryIndex = categoryElement.getAttribute('data-id')
    const interpretationContainer = document.getElementById('interpretation-container-' + categoryIndex);
    const interpretationCount = interpretationContainer.querySelectorAll('.question-interpretation').length;
    const addNewInterpretationButton = document.getElementById('add-new-interpretation-button-' + categoryIndex);
    if (interpretationCount == 0) {
      addNewInterpretationButton.click();
    }
  })
});

const deleteQuestion = (button) => {
  const index = button.getAttribute('data-id');
  const questionElement = document.getElementById('accordion-' + index);
  if (questionElement) {
    questionElement.remove();
  }
};

const deleteOption = (button) => {
  const optionElement = button.closest('.question-option');
  if (optionElement) {
    optionElement.remove();
  }
};

const addNewQuestionOption = (e) => {
  const questionIndex = e.target.getAttribute("data-id")
  const optionContainer = document.getElementById('option-container-' + questionIndex)
  const existingOptionsCount = optionContainer.querySelectorAll('.question-option').length;
  const optionTemplate = document.getElementById('question-option-template').innerHTML;
  newOptionHtml = optionTemplate.replace(/__OPTIONINDEX__/g, existingOptionsCount).replace(/__INDEX__/g, questionIndex)
  optionContainer.insertAdjacentHTML('beforeend', newOptionHtml);
}


const addNewInterpretation = (e) => {
  const interpretationContainerIndex = e.target.getAttribute("data-id")
  const interpretationContainer = document.getElementById('interpretation-container-' + interpretationContainerIndex)
  const existingInterpretationCount = interpretationContainer.querySelectorAll('.question-interpretation').length;
  const interpretationTemplate = document.getElementById('interpretation-template').innerHTML;
  newInterpretationHtml = interpretationTemplate.replace(/__INTERPRETATIONINDEX__/g, existingInterpretationCount).replace(/__INDEX__/g, interpretationContainerIndex)
  interpretationContainer.insertAdjacentHTML('beforeend', newInterpretationHtml);
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

validateForms();


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

// Disable-Enable Audience & Access Checkbox JS
document.addEventListener('DOMContentLoaded', function() {
  var targetaudiencecheckboxes = document.querySelectorAll('input[id^="targetaudience_"]');
  var accesstoresponsecheckboxes = document.querySelectorAll('input[id^="accesstoresponse_"]');
  var continueButton = document.getElementById('continue-button');

  function toggleButton() {
    var isTargetAudienceChecked = Array.from(targetaudiencecheckboxes).some(checkbox => checkbox.checked);
    var isAccessResponseChecked = Array.from(accesstoresponsecheckboxes).some(checkbox => checkbox.checked);

    var isEnabled = isTargetAudienceChecked && isAccessResponseChecked;

    continueButton.classList.toggle('disabled', !isEnabled);
    continueButton.setAttribute('aria-disabled', !isEnabled);
    continueButton.style.pointerEvents = isEnabled ? 'auto' : 'none';
    continueButton.style.opacity = isEnabled ? '1' : '0.5';
  }

  targetaudiencecheckboxes.forEach(checkbox => checkbox.addEventListener('change', toggleButton));
  accesstoresponsecheckboxes.forEach(checkbox => checkbox.addEventListener('change', toggleButton));

  toggleButton();
});

document.addEventListener('DOMContentLoaded', function() {
  const selectElement = document.getElementById('survey-insight-type');
  const questionCategorySelectElement = document.getElementById('question-category-select');
  selectElement.addEventListener('change', function() {
      document.getElementById('filter-form').submit();
  });

  questionCategorySelectElement.addEventListener('change', function() {
    document.getElementById('filter-category-form').submit();
  });

  selectElement.addEventListener('click', function(event) {
    event.stopPropagation();
  });
});