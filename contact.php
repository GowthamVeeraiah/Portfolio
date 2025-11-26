<script>
document.addEventListener('DOMContentLoaded', function(){
  const contactForm = document.getElementById('contactForm');
  if(!contactForm) return;

  const showAlert = (type, text) => {
    // Simple inline feedback; you can improve UI later
    alert(text);
  };

  contactForm.addEventListener('submit', async function(e){
    e.preventDefault();

    const formData = new FormData(contactForm);
    // Optional: simple client-side validation before sending
    const name = formData.get('name')?.trim();
    const email = formData.get('email')?.trim();
    const subject = formData.get('subject')?.trim();
    const message = formData.get('message')?.trim();
    if(!name || !email || !subject || !message){
      showAlert('error', 'Please fill all fields.');
      return;
    }

    try {
      const res = await fetch('contact.php', {
        method: 'POST',
        body: formData
      });
      const data = await res.json();

      if(data.status === 'success') {
        showAlert('success', data.message || 'Message sent.');
        contactForm.reset();
      } else {
        // data.errors is an array of validation messages from PHP
        const errText = (data.errors && data.errors.length) ? data.errors.join('\\n') : (data.message || 'Submission failed');
        showAlert('error', errText);
      }
    } catch (err) {
      console.error('Request error', err);
      showAlert('error', 'Network error — please try again.');
    }
  });
});
</script>
