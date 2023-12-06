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
    let newStock = document.getElementById('new_stock_' + ticketTypeId).value;
    if (newStock == 0){
      const label = document.getElementById('label' + ticketTypeId);
      const input = document.getElementById('input' + ticketTypeId);

      label.style.display = 'none';
      input.style.display = 'none';
    }
    else {
      
      const label = document.getElementById('label' + ticketTypeId);
      const input = document.getElementById('input' + ticketTypeId);
      if (label){
        label.style.display = 'flex';
        input.style.display = 'flex';
      } else {
        const container = document.querySelector('#ticket-type-'+ticketTypeId);
        const max = container.getAttribute('data-max');
        console.log(container.innerHTML);
        let original = container.innerHTML;
        const update = original + ` <label class="quant" id ="label${ticketTypeId}" for="quantity_${ticketTypeId}">Quantity:</label>
        <input class="quant" id ="input${ticketTypeId}" type="number" id="quantity_${ticketTypeId}" name="quantity[${ticketTypeId}]" min="0" max="${max}">
        `;
        container.innerHTML = update;
      }
   
  }
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
  document.getElementById('user-header-name').innerText = formData.edit_name;

  }

function updateProfile() {

  document.getElementById('update-profile-button').style.display = 'none';
  document.getElementById('edit-profile-button').style.display = 'block';
  document.getElementById('edit_name').disabled = true;
  document.getElementById('edit_email').disabled = true;
  document.getElementById('edit_promotor_code').disabled = true;
  document.getElementById('edit_phone_number').disabled = true;


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
  let ticketName = document.getElementById('ticket_name').value;
  let ticketStock = document.getElementById('ticket_stock').value;
  let ticketPersonLimit = document.getElementById('ticket_person_limit').value;
  let ticketPrice = document.getElementById('ticket_price').value || 0;
  let ticketStartTimestamp = document.getElementById('ticket_start_timestamp').value;
  let ticketEndTimestamp = document.getElementById('ticket_end_timestamp').value;

  if (!ticketName || !ticketStock || !ticketPersonLimit || !ticketStartTimestamp || !ticketEndTimestamp) {
      alert("Todos os campos são obrigatórios.");
      return;
  }

  if (isNaN(ticketPrice)) {
      alert("O preço do ingresso deve ser um número.");
      return;
  }

  let currentDate = new Date().toISOString().split('T')[0];
  if (ticketStartTimestamp < currentDate) {
      alert("A data de início do ingresso deve ser igual ou superior à data atual.");
      return;
  }

  if (ticketEndTimestamp <= currentDate) {
      alert("A data de término do ingresso deve ser superior à data atual.");
      return;
  }

  if (ticketStartTimestamp.split('T')[0] === ticketEndTimestamp.split('T')[0] && ticketStartTimestamp.split('T')[1] >= ticketEndTimestamp.split('T')[1]) {
    alert("A hora de início do ingresso deve ser anterior à hora de término no mesmo dia.");
    return;
  }

  let formData = {
      'ticket_name': ticketName,
      'ticket_stock': ticketStock,
      'ticket_description': document.getElementById('ticket_description').value,
      'ticket_person_limit': ticketPersonLimit,
      'ticket_price': ticketPrice,
      'ticket_start_timestamp': ticketStartTimestamp,
      'ticket_end_timestamp': ticketEndTimestamp,
  };

  sendAjaxRequest('post', `../create-ticket-type/${event_id}`, formData);

  updateTicketPageContent(formData);
}




addEventListeners();





const activate = document.querySelector('#activate-button');

if (activate) {
  activate.addEventListener('click', function(){
    const eventId = activate.getAttribute('data-id');
    if (activate.textContent == 'Activate Event') {
      sendAjaxRequest('post', '/activate-event/' + eventId, {}, eventHandler)
     
    } else {
     sendAjaxRequest('post', '/deactivate-event/' + eventId, {}, event2Handler)
   }
  })
}


function eventHandler(){
  if (this.status == 200){
    console.log('Ativado');
    activate.textContent = 'Deactivate Event'
    activate.classList.remove('active')
  }
}

function event2Handler(){
  if (this.status == 200){
    console.log('Desativado');
    activate.textContent = 'Activate Event'
    activate.classList.add('active')
  }
}

function toggleProfileButtons() {
  document.getElementById('edit-profile-button').style.display = 'none';
  document.getElementById('update-profile-button').style.display = 'block';

    document.getElementById('edit_name').disabled = false;
    document.getElementById('edit_email').disabled = false;
    document.getElementById('edit_promotor_code').disabled = false;
    document.getElementById('edit_phone_number').disabled = false;
}

function toggleCheckoutSection() {
  var checkoutSection = document.getElementById('checkout-section');
  var buyButton = document.getElementById('buy-button');
  var showForm = document.getElementById('show-form')

  checkoutSection.style.display = 'block';
  buyButton.style.display = 'inline';
  showForm.style.display = 'none';
}