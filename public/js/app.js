function addEventListeners() {
  let activeUsersSection = document.getElementById('active_users_section');
  if (activeUsersSection) {
    activeUsersSection.addEventListener('click', function(event) {
      if (event.target.classList.contains('deactivate-btn')) {
        let userId = event.target.getAttribute('data-user-id');
        deactivateUser(userId);
      }
    });
  }

  let inactiveUsersSection = document.getElementById('inactive_users_section');
  if (inactiveUsersSection) {
    inactiveUsersSection.addEventListener('click', function(event) {
      if (event.target.classList.contains('activate-btn')) {
        let userId = event.target.getAttribute('data-user-id');
        activateUser(userId);
      }
    });
  }
}
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }

  function sendAjaxRequest(method, url, data, handler) {
    let request = new XMLHttpRequest();

    request.open(method, url, true);
    request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.addEventListener('load', handler);
    request.send(encodeForAjax(data));
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

  
  
  function updateEventPageContent(formData) {
      document.getElementById('name').innerHTML = formData.edit_name;
      document.getElementById('location').innerHTML =  formData.edit_location;
      document.getElementById('description').innerHTML =  formData.edit_description;
      //document.getElementById('start_timestamp').innerHTML =  formData.edit_start_timestamp; //ainda não está display
      //document.getElementById('end_timestamp').innerHTML =  formData.edit_end_timestamp;  //ainda não está display
  }

  function updateEvent(eventId) {

      let formData = {
          'edit_name': document.getElementById('edit_name').value,
          'edit_description': document.getElementById('edit_description').value,
          'edit_location': document.getElementById('edit_location').value,
          'edit_start_timestamp': document.getElementById('edit_start_timestamp').value,
          'edit_end_timestamp': document.getElementById('edit_end_timestamp').value
      };

      updateEventPageContent(formData);

      sendAjaxRequest('post', '../update-event/' + eventId, formData);

      //colocar uma mensagem a dizer que foi alterado
  }

function updateProfilePageContent(formData) {
    document.getElementById('user_name').innerHTML = "Name: " + formData.edit_name;
    document.getElementById('user_email').innerHTML = "Email: " + formData.edit_email;
    document.getElementById('user_promotor_code').innerHTML = "Promotor Code: " + formData.edit_promotor_code;
    document.getElementById('user_phone_number').innerHTML = "Phone Number: " + formData.edit_phone_number;
  }

function updateProfile() {
  let formData = {
      'edit_name': document.getElementById('edit_name').value,
      'edit_email': document.getElementById('edit_email').value,
      'edit_promotor_code': document.getElementById('edit_promotor_code').value,
      'edit_phone_number': document.getElementById('edit_phone_number').value,
  };

  console.log(formData);


  sendAjaxRequest('post', '../update-profile', formData);
  
  updateProfilePageContent(formData);
  
  //depois tenho de colocar uma mensagem a dizer que foi alterado

}

function updateTicketPageContent(formData) {

  let ticketTypesContainer = document.getElementById('ticket-types-container');
  let newTicketType = document.createElement('article');
  newTicketType.className = 'ticket-type'; 
  newTicketType.innerHTML = `
      <h3>${formData.ticket_name}</h3>
      <p>Stock: ${formData.ticket_stock}</p>
      <p>Description: ${formData.ticket_description}</p>
      <p>Price: ${formData.ticket_price} €</p>
      <label for="quantity_${formData.ticket_type_id}">Quantity:</label>
      <input type="number" id="quantity_${formData.ticket_type_id}" name="quantity[${formData.ticket_type_id}]" min="0" max="${formData.ticket_person_limit}">
  `;
  ticketTypesContainer.appendChild(newTicketType);

  document.getElementById('ticket_name').value = '';
  document.getElementById('ticket_stock').value = '';
  document.getElementById('ticket_description').value = '';
  document.getElementById('ticket_person_limit').value = '';
  document.getElementById('ticket_price').value = '';
  document.getElementById('ticket_start_timestamp').value = '';
  document.getElementById('ticket_end_timestamp').value = '';
}

function createTicketType(event_id) {

  let formData = {
    'ticket_name': document.getElementById('ticket_name').value,
    'ticket_stock': document.getElementById('ticket_stock').value,
    'ticket_description': document.getElementById('ticket_description').value,
    'ticket_person_limit': document.getElementById('ticket_person_limit').value,
    'ticket_price': document.getElementById('ticket_price').value,
    'ticket_start_timestamp': document.getElementById('ticket_start_timestamp').value, 
    'ticket_end_timestamp': document.getElementById('ticket_end_timestamp').value, 
  };

  updateTicketPageContent(formData);

  sendAjaxRequest('post', `../create-ticket-type/${event_id}`, formData);

}


addEventListeners();
