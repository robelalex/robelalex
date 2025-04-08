const serviceBtns = document.querySelectorAll(".service-btn");
const serviceInfos = document.querySelectorAll(".service-info");
const forms = document.querySelectorAll(".service-form");

serviceBtns.forEach((btn) => {
  btn.addEventListener("click", function () {
    const service = this.getAttribute("data-service");

    // Remove active class from all buttons and info divs
    serviceBtns.forEach((btn) => btn.classList.remove("active"));
    serviceInfos.forEach((info) => info.classList.remove("active"));

    // Add active class to clicked button and corresponding info div
    this.classList.add("active");
    document.getElementById(service).classList.add("active");
  });
});

forms.forEach((form) => {
  form.addEventListener("submit", function (event) {
    event.preventDefault();

    const formData = new FormData(this);

    fetch(this.action, {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        return response.json(); // Parse JSON response
      })
      .then((data) => {
        console.log("Success:", data);

        showNotification(data.message, data.success);

        if (data.success) {
          form.reset();
        }
      })
      .catch(() => {
        showNotification("An error occurred. Please try again.", false);
      });
  });
});

function showNotification(message, success) {
    const container = document.getElementById('notification-container');
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${success ? 'success' : 'error'}`;
    
    // Create icon element
    const icon = document.createElement('div');
    icon.className = 'notification-icon';
    icon.innerHTML = success 
        ? '<svg viewBox="0 0 24 24" width="24" height="24" fill="#4CAF50"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"></path></svg>'
        : '<svg viewBox="0 0 24 24" width="24" height="24" fill="#F44336"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path></svg>';
    
    // Create content element
    const content = document.createElement('div');
    content.className = 'notification-content';
    content.textContent = message;
    
    // Create close button
    const closeBtn = document.createElement('div');
    closeBtn.className = 'notification-close';
    closeBtn.innerHTML = '×';
    closeBtn.onclick = () => removeNotification(notification);

    notification.appendChild(icon);
    notification.appendChild(content);
    notification.appendChild(closeBtn);
    
    
    container.appendChild(notification);
    
    // Set automatic removal
    setTimeout(() => {
        removeNotification(notification);
    }, 5000); 
}

function removeNotification(notification) {
    notification.style.animation = 'slideOut 0.5s ease forwards';
    
    // Remove element after animation
    notification.addEventListener('animationend', () => {
        if (notification.parentElement) {
            notification.parentElement.removeChild(notification);
        }
    });
}
