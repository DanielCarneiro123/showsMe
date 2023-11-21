function addEventListeners() {
  // Attach event listener to the parent table
  document.getElementById('active_users_section').addEventListener('click', function(event) {
    // Check if the clicked element is a deactivate button
    if (event.target.classList.contains('deactivate-btn')) {
      let userId = event.target.getAttribute('data-user-id');
      deactivateUser(userId);
    }
  });

  // Attach event listener to the parent table
  document.getElementById('inactive_users_section').addEventListener('click', function(event) {
    // Check if the clicked element is an activate button
    if (event.target.classList.contains('activate-btn')) {
      let userId = event.target.getAttribute('data-user-id');
      activateUser(userId);
    }
  });
}

  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  

  function updateStockContent(formData, ticketTypeId) {
    document.getElementById('new_stock_' + ticketTypeId ).innerHTML = formData['new_stock_' + ticketTypeId];
    document.getElementById('stock_display_' + ticketTypeId ).innerHTML = 'Stock: ' + formData['new_stock_' + ticketTypeId];
  }

  function moveUserToInactiveTable(userId) {
    let activeUserRow = document.getElementById('active_user_row_' + userId);

    if (activeUserRow) {
        let activateButton = activeUserRow.querySelector('.deactivate-btn');
        activeUserRow.parentNode.removeChild(activeUserRow);
        activeUserRow.id = 'inactive_user_row_' + userId;
        let inactiveUsersTable = document.getElementById('inactive_users_section').querySelector('tbody');

        inactiveUsersTable.appendChild(activeUserRow);

        activateButton.innerText = 'Activate';
        activateButton.classList.remove('deactivate-btn');
        activateButton.classList.add('activate-btn');
        activateButton.removeEventListener('click', deactivateUser);
        activateButton.addEventListener('click', function() {
            activateUser(userId);
        });
    } else {
        console.error('Active user row not found:', userId);
    }
}

function moveUserToActiveTable(userId) {
  let inactiveUserRow = document.getElementById('inactive_user_row_' + userId);

  if (inactiveUserRow) {
      let deactivateButton = inactiveUserRow.querySelector('.activate-btn');
      inactiveUserRow.parentNode.removeChild(inactiveUserRow);
      inactiveUserRow.id = 'active_user_row_' + userId;
      let activeUsersTable = document.getElementById('active_users_section').querySelector('tbody');

      activeUsersTable.appendChild(inactiveUserRow);

      deactivateButton.innerText = 'Deactivate';
      deactivateButton.classList.remove('activate-btn');
      deactivateButton.classList.add('deactivate-btn');
      deactivateButton.removeEventListener('click', activateUser);
      deactivateButton.addEventListener('click', function() {
          deactivateUser(userId);
      });
  } else {
      console.error('Inactive user row not found:', userId);
  }
}


  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();
  
    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
  }

  function updateStock(ticketTypeId) {
    let newStock = document.getElementById('new_stock_' + ticketTypeId).value;

    let formData = {
      ['new_stock_' + ticketTypeId]: newStock
  };

    sendAjaxRequest('POST', '/update-ticket-stock/' + ticketTypeId, formData, function () {
        if (this.status === 200) {
            let response = JSON.parse(this.responseText);
            updateStockContent(response, ticketTypeId);
        } else {
            console.error('Error updating stock:', this.responseText);
        }
    });
}

function deactivateUser(userId) {
  let formData = { 'user_id': userId };

  sendAjaxRequest('PUT', '/deactivateUser/' + userId, formData, moveUserToInactiveTable(userId));
}

function activateUser(userId) {
  let formData = { 'user_id': userId };

  sendAjaxRequest('PUT', '/activateUser/' + userId, formData, moveUserToActiveTable(userId)); 
}


  addEventListeners();

  
  