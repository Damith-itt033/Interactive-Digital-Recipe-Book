//Contact Form Validation Module (js/contact-validation.js)

document.addEventListener('DOMContentLoaded', () => {

  const contactForm = document.getElementById('contactForm');
  const fullNameInput = document.getElementById('fullName');
  const emailInput = document.getElementById('emailAddr');
  const msgInput = document.getElementById('msgContent');
  const charCounter = document.getElementById('charCounter');
  const contactToastEl = document.getElementById('contactToast');

  if (!contactForm) return;

  // Real-time Email Regex Pattern Validation
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function validateField(input, isConditionValid) {
    if (isConditionValid) {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
      return true;
    } else {
      input.classList.remove('is-valid');
      input.classList.add('is-invalid');
      return false;
    }
  }

  // Real-time Name Input Event
  if (fullNameInput) {
    fullNameInput.addEventListener('input', () => {
      validateField(fullNameInput, fullNameInput.value.trim().length >= 3);
    });
  }

  // Real-time Email Input Event
  if (emailInput) {
    emailInput.addEventListener('input', () => {
      validateField(emailInput, emailRegex.test(emailInput.value.trim()));
    });
  }

  // Real-time Message Input Event & Character Count
  if (msgInput) {
    msgInput.addEventListener('input', () => {
      const len = msgInput.value.length;
      if (charCounter) charCounter.textContent = `${len} / 500`;
      validateField(msgInput, len >= 10 && len <= 500);
    });
  }

  // Form Submit Event Handler
  contactForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const isNameValid = validateField(fullNameInput, fullNameInput.value.trim().length >= 3);
    const isEmailValid = validateField(emailInput, emailRegex.test(emailInput.value.trim()));
    const isMsgValid = validateField(msgInput, msgInput.value.length >= 10 && msgInput.value.length <= 500);

    if (isNameValid && isEmailValid && isMsgValid) {
      // Trigger Success Toast
      if (contactToastEl) {
        const toast = new bootstrap.Toast(contactToastEl);
        document.getElementById('toastMessage').textContent = `Thank you, ${fullNameInput.value.trim()}! Your inquiry has been submitted successfully.`;
        toast.show();
      }

      // Reset Form
      contactForm.reset();
      [fullNameInput, emailInput, msgInput].forEach(el => {
        el.classList.remove('is-valid', 'is-invalid');
      });
      if (charCounter) charCounter.textContent = '0 / 500';
    }
  });

});
