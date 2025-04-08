const forms = document.querySelectorAll(".userAuth");
console.log(forms);

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
  
          showAuthNotification(data.type, data.message, data.title);
  
          if (data.success) {
            form.reset();
          }
        })
        .catch(() => {
            showAuthNotification('error', "An error occurred. Please try again.", 'Error!');
        });
    });
  });

function showAuthNotification(type, message, title) {
    const container = document.getElementById('notification-container');
    const notification = document.createElement('div');
    
    switch(type) {
        case 'signup':
            icon = '<svg viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12zm3.707-8.707l-4 4a1 1 0 01-1.414 0l-2-2 1.414-1.414L9 9.586l3.293-3.293 1.414 1.414z"/></svg>';
            break;
        case 'signin':
            icon = '<svg viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 12.5v-1.5h2v1.5a6 6 0 106-6h1.5a7.5 7.5 0 11-9.5 6zm9-5.5H9.414l2.293-2.293-1.414-1.414L6 10l4.293 4.293 1.414-1.414L9.414 9H18V7z"/></svg>';
            break;
        case 'error':
            icon = '<svg viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 12H9v-2h2v2zm0-3H9V6h2v5z"/></svg>';
            break;
    }

    notification.className = `auth-notification ${type}`;
    notification.innerHTML = `
        <div class="auth-icon">${icon}</div>
        <div class="auth-content">
            <div class="auth-title">${title}</div>
            <div class="auth-message">${message}</div>
        </div>
        <div class="auth-close" onclick="closeNotification(this.parentElement)">×</div>
        <div class="progress-bar"></div>
    `;

    container.appendChild(notification);

    // Auto remove after 3 seconds
    setTimeout(() => {
        closeNotification(notification);
    }, 3000);
}

function closeNotification(notification) {
    notification.style.animation = 'slideOut 0.5s forwards';
    setTimeout(() => {
        notification && notification.parentElement && 
        notification.parentElement.removeChild(notification);
    }, 500);
}