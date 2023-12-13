function addEventListeners() {
  let activeUsersSection = document.getElementById('active_users_section');
  if (activeUsersSection) {
    activeUsersSection.addEventListener('click', function (event) {
      if (event.target.classList.contains('deactivate-btn')) {
        let userId = event.target.getAttribute('data-user-id');
        deactivateUser(userId);
      }
    });
  }

  let inactiveUsersSection = document.getElementById('inactive_users_section');
  if (inactiveUsersSection) {
    inactiveUsersSection.addEventListener('click', function (event) {
      if (event.target.classList.contains('activate-btn')) {
        let userId = event.target.getAttribute('data-user-id');
        activateUser(userId);
      }
    });
  }
}

function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function (k) {
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
  document.getElementById('new_stock_' + ticketTypeId).innerHTML = formData['new_stock_' + ticketTypeId];
  document.getElementById('stock_display_' + ticketTypeId).innerHTML = 'Stock: ' + formData['new_stock_' + ticketTypeId];
  let newStock = document.getElementById('new_stock_' + ticketTypeId).value;
  if (newStock == 0) {
    const label = document.getElementById('label' + ticketTypeId);
    const input = document.getElementById('input' + ticketTypeId);

    label.style.display = 'none';
    input.style.display = 'none';
  }
  else {

    const label = document.getElementById('label' + ticketTypeId);
    const input = document.getElementById('input' + ticketTypeId);
    if (label) {
      label.style.display = 'flex';
      input.style.display = 'flex';
    } else {
      const container = document.querySelector('#ticket-type-' + ticketTypeId);
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
    activateButton.addEventListener('click', function () {
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
    deactivateButton.addEventListener('click', function () {
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

  updateInactiveUserCount();
  updateActiveUserCount();
  updateActiveEventCount();
  updateInactiveEventCount();
  updateEventCountByMonth();
  updateEventCountByDay();
  updateEventCountByYear();
}

function activateUser(userId) {
  let formData = { 'user_id': userId };

  sendAjaxRequest('PUT', '/activateUser/' + userId, formData, moveUserToActiveTable(userId));

  updateInactiveUserCount();
  updateActiveUserCount();
  updateActiveEventCount();
  updateInactiveEventCount();
  updateEventCountByMonth();
  updateEventCountByDay();
  updateEventCountByYear();
}

function updateActiveUserCount() {
  sendAjaxRequest('GET', '/getActiveUserCount', null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('activeUserCount').innerText = 'Total de usuários ativos: ' + count;
  });
}

function updateInactiveUserCount() {
  sendAjaxRequest('GET', '/getInactiveUserCount', null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('inactiveUserCount').innerText = 'Total de usuários inativos: ' + count;
  });
}

function updateActiveEventCount() {
  sendAjaxRequest('GET', '/getActiveEventCount', null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('activeEventCount').innerText = count;
  });
}

function updateInactiveEventCount() {
  sendAjaxRequest('GET', '/getInactiveEventCount', null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('inactiveEventCount').innerText = count;
  });
}

function updateEventPageContent(formData) {
  document.getElementById('name').innerHTML = formData.edit_name;
  document.getElementById('location').innerHTML = formData.edit_location;
  document.getElementById('description').innerHTML = formData.edit_description;
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

function updateTicketPageContent(ticketType) {
  console.log(ticketType);
  let ticketTypesContainer = document.getElementById('ticket-types-container');
  let newTicketType = document.createElement('article');
  newTicketType.className = 'ticket-type';
  newTicketType.innerHTML = `
      <h3>${ticketType.name}</h3>
      <p>Stock: ${ticketType.stock}</p>
      <p>Description: ${ticketType.description}</p>
      <p>Price: ${ticketType.price} €</p>
      <label for="quantity_${ticketType.ticket_type_id}">Quantity:</label>
      <input type="number" id="quantity_${ticketType.ticket_type_id}" name="quantity[${ticketType.ticket_type_id}]" min="0" max="${ticketType.person_buying_limit}">
      
      <!-- New Stock -->
      <p>New Stock:
      <input type="number" id="new_stock_${ticketType.ticket_type_id}" name="new_stock" value="${ticketType.stock}" required>
      </p>
      <button class="button-update-stock" onclick="updateStock(${ticketType.ticket_type_id})" form="purchaseForm">Update Stock</button>
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

async function createTicketType(event_id) {
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

  let eventStartTimestamp = document.getElementById('edit_start_timestamp').value;
  let eventEndTimestamp = document.getElementById('edit_end_timestamp').value;

  if (ticketStartTimestamp < eventStartTimestamp || ticketStartTimestamp >= eventEndTimestamp) {
    alert("A data de início do ingresso deve ser maior ou igual à data de início do evento e menor que a data de fim do evento.");
    return;
  }

  if ((ticketEndTimestamp && ticketEndTimestamp <= eventStartTimestamp) || (ticketEndTimestamp && ticketEndTimestamp > eventEndTimestamp)) {
    alert("A data de término do ingresso deve ser maior que a data de início do evento e menor ou igual à data de fim do evento.");
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

  sendAjaxRequest('post', `../create-ticket-type/${event_id}`, formData, createTypeHandler);

}


function createTypeHandler() {
  if (this.status == 200) {
    const response = JSON.parse(this.responseText);
    const ticketType = response.ticketType;
    console.log(ticketType.ticket_type_id);
    updateTicketPageContent(ticketType);

  }
}


const activate = document.querySelector('#activate-button');

if (activate) {
  activate.addEventListener('click', function () {
    const eventId = activate.getAttribute('data-id');
    if (activate.textContent == 'Activate Event') {
      sendAjaxRequest('post', '/activate-event/' + eventId, {}, eventHandler)

    } else {
      sendAjaxRequest('post', '/deactivate-event/' + eventId, {}, event2Handler)
    }
  })
}


function eventHandler() {
  if (this.status == 200) {
    console.log('Ativado');
    activate.textContent = 'Deactivate Event'
    activate.classList.remove('active')
  }
}

function event2Handler() {
  if (this.status == 200) {
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




//Immediate Execution on Page Load (To follow this approach later, if there is time)

function showSection() {
  var sectionButtons = document.querySelectorAll('.btn-check');
  var eventSections = document.getElementsByClassName("event-section");

  if (!sectionButtons.length || !eventSections.length) {
    return;
  }

  for (var j = 0; j < eventSections.length - 1; j++) {
    eventSections[j].style.display = "none";
  }

  sectionButtons.forEach(function (button) {
    button.addEventListener("click", function () {
      console.log(this);

      var sectionId = this.getAttribute("data-section-id");
      console.log("Section ID:", sectionId);

      var currentSection = document.getElementById(sectionId);

      for (var j = 0; j < eventSections.length; j++) {
        eventSections[j].style.display = "none";
      }

      if (currentSection) {
        currentSection.style.display = "block";
        console.log("Displaying section with ID:", sectionId);
      } else {
        console.log("Section not found with ID:", sectionId);
      }

      sectionButtons.forEach(function (btn) {
        btn.parentElement.classList.remove("selected");
      });

      this.parentElement.classList.add("selected");
      console.log("Button marked as selected");
    });
  });
}

showSection();

document.addEventListener("DOMContentLoaded", function () {
  let totalFields = document.querySelectorAll(".form-field").length;

  function updateProgressBar() {
    let filledFields = Array.from(document.querySelectorAll(".form-field")).filter(function (field) {
      return field.value.trim() !== "";
    }).length;

    let progress = (filledFields / totalFields) * 100;
    document.querySelector("#progress-bar-container .progress-bar").style.width = progress + "%";
    document.querySelector("#progress-bar-container .progress-bar").setAttribute("aria-valuenow", progress);
  }

  document.querySelectorAll(".form-field").forEach(function (field) {
    field.addEventListener("input", updateProgressBar);
    field.addEventListener("change", updateProgressBar);
  });
});


addEventListeners();


function toggleCheckoutSection() {
  var checkoutSection = document.getElementById('checkout-section');
  var buyButton = document.getElementById('buy-button');
  var showForm = document.getElementById('show-form')

  checkoutSection.style.display = 'block';
  buyButton.style.display = 'inline';
  showForm.style.display = 'none';
}

function showEditCommentModal() {
  const comment = event.target.closest(".comment");

  comment.querySelector('#commentText').style.display = 'none';
  

  comment.querySelector('#editCommentForm').style.display = 'block';
 
}


function hideEditCommentModal() {
  const comment = event.target.closest(".comment");

  comment.querySelector('#commentText').style.display = 'block';
  

  comment.querySelector('#editCommentForm').style.display = 'none';
 
}

function unlikeComment(){
  const comment = event.target.closest(".comment");
  console.log(comment);
  const commentID = comment.getAttribute('data-id');
  console.log(commentID);
  event.preventDefault();
  sendAjaxRequest('post', '/unlike-comment', {comment_id: commentID} , unlikeCommentHandler);
}

function unlikeCommentHandler() {
  console.log(this.responseText);
  
}

function likeComment(){
  const comment = event.target.closest(".comment");

  const commentID = comment.getAttribute('data-id');

  event.preventDefault();
  sendAjaxRequest('post', '/like-comment',{comment_id: commentID} , likeCommentHandler);
}

function likeCommentHandler() {
  const response = JSON.parse(this.responseText);
  const message = response.message;

  if (message && message.comment_id) {
    const commentId = message.comment_id;
    
    
    const commentElement = document.querySelector(`.comment[data-id="${commentId}"]`);
    
    if (commentElement) {
      console.log('WOrked');
    } else {
      console.error('Comment element not found in HTML:', commentId);
    }
  } else {
    console.error('Invalid response structure or missing comment_id.');
  }
}

function deleteComment(){
  const comment = event.target.closest(".comment");

  const commentID = comment.getAttribute('data-id');

  event.preventDefault();
  sendAjaxRequest('post', '/delete-comment',{comment_id: commentID} , deleteCommentHandler);
}
function deleteCommentHandler() {
  const response = JSON.parse(this.responseText);
  const message = response.message;

  if (message && message.comment_id) {
    const commentId = message.comment_id;
    
    
    const commentElement = document.querySelector(`.comment[data-id="${commentId}"]`);
    
    if (commentElement) {
      commentElement.remove();
    } else {
      console.error('Comment element not found in HTML:', commentId);
    }
  } else {
    console.error('Invalid response structure or missing comment_id.');
  }
}

function editComment(){
  const comment = event.target.closest(".comment");
  
  
  commentID = comment.getAttribute('data-id');
  commentText = comment.querySelector('#editedCommentText').value;
  
  event.preventDefault();
  sendAjaxRequest('post', '/edit-comment',{newCommentText: commentText,comment_id: commentID} , editCommentHandler);

};

function editCommentHandler() {
  if (this.status === 200) {
    const response = JSON.parse(this.responseText);
    const editedComment = response.message;

    // Ensure that editedComment.comment_id and editedComment.text are defined
    if (editedComment && editedComment.comment_id && editedComment.text) {
      // Find the comment element by data-id attribute
      const commentElement = document.querySelector(`.comment[data-id="${editedComment.comment_id}"]`);

      if (commentElement) {
        // Update the displayed text of the comment
        const commentTextElement = commentElement.querySelector('.comment-text');
        if (commentTextElement) {
          commentTextElement.textContent = editedComment.text;

          // Hide the editCommentForm and display #commentText
          const editCommentForm = commentElement.querySelector('#editCommentForm');
          const commentText = commentElement.querySelector('#commentText');

          if (editCommentForm && commentText) {
            editCommentForm.style.display = 'none';
            commentText.style.display = 'block';
          } else {
            console.error('editCommentForm or commentText element not found.');
          }
        }
      } else {
        console.error('Comment element not found.');
      }
    } else {
      console.error('Invalid response structure or missing comment ID or text.');
    }
  }
}


function addNewComment(){
  
  eventID = document.querySelector('#newCommentEventID').value;
  commentText = document.querySelector('#newCommentText').value;
  
  event.preventDefault();
  sendAjaxRequest('post', '/submit-comment',{newCommentText: commentText,event_id: eventID} , addNewCommentHandler);

};


function addNewCommentHandler() {
  if (this.status === 200) {
    const response = JSON.parse(this.responseText);
    const newComment = response.message;

    // Ensure that newComment.text and newComment.author_id are defined
    if (newComment && newComment.text && newComment.author && newComment.author.name) {
      // Create a new comment element
      const commentElement = document.createElement('div');
      commentElement.className = 'comment';
      commentElement.setAttribute('data-id', newComment.comment_id);

      const commentIconsContainer = document.createElement('div');
      commentIconsContainer.className = 'comment-icons-container';

      const commentAuthor = document.createElement('p');
      commentAuthor.className = 'comment-author';
      commentAuthor.textContent = newComment.author.name; // Use the actual author name

      const iconsDiv = document.createElement('div');

      // Edit icon
      const editIcon = document.createElement('i');
      editIcon.className = 'fa-solid fa-pen-to-square';
      editIcon.addEventListener('click', function () {
        // Hide commentText when edit icon is clicked
        const commentText = commentElement.querySelector('.comment-text');
        if (commentText) {
          commentText.style.display = 'none';
        }

        // Show the edit comment form
        const editCommentForm = commentElement.querySelector('#editCommentForm');
        if (editCommentForm) {
          editCommentForm.style.display = 'block';
        }
      });
      iconsDiv.appendChild(editIcon);

      // Trash can icon
      const deleteIcon = document.createElement('i');
      deleteIcon.className = 'fa-solid fa-trash-can';
      deleteIcon.addEventListener('click', function () {
        const commentID = commentElement.getAttribute('data-id');
        event.preventDefault();
        sendAjaxRequest('post', '/delete-comment', { comment_id: commentID }, deleteCommentHandler);
      });
      iconsDiv.appendChild(deleteIcon);

      commentIconsContainer.appendChild(commentAuthor);
      commentIconsContainer.appendChild(iconsDiv);

      const commentText = document.createElement('p');
      commentText.className = 'comment-text';
      commentText.id = 'commentText';
      commentText.textContent = newComment.text;

      const editCommentForm = document.createElement('form');
      editCommentForm.id = 'editCommentForm';
      editCommentForm.style.display = 'none';

      const editedCommentText = document.createElement('textarea');
      editedCommentText.id = 'editedCommentText';
      editedCommentText.className = 'edit-comment-textbox';
      editedCommentText.rows = '3';
      editedCommentText.value = newComment.text;
      editedCommentText.required = true;

      const submitButton = document.createElement('button');
      submitButton.className = 'btn btn-primary';
      submitButton.textContent = 'Submit';
      submitButton.addEventListener('click', function () {
        const comment = event.target.closest('.comment');
        const commentID = comment.getAttribute('data-id');
        const editedCommentText = comment.querySelector('#editedCommentText').value;

        event.preventDefault();
        sendAjaxRequest('post', '/edit-comment', { newCommentText: editedCommentText, comment_id: commentID }, editCommentHandler);
      });

      const cancelButton = document.createElement('button');
      cancelButton.className = 'btn btn-danger';
      cancelButton.textContent = 'Cancel';
      cancelButton.type = 'button';
      cancelButton.addEventListener('click', function () {
        const comment = event.target.closest('.comment');
        comment.querySelector('#commentText').style.display = 'block';
        comment.querySelector('#editCommentForm').style.display = 'none';
      });

      editCommentForm.appendChild(editedCommentText);
      editCommentForm.appendChild(submitButton);
      editCommentForm.appendChild(cancelButton);

      commentElement.appendChild(commentIconsContainer);
      commentElement.appendChild(commentText);
      commentElement.appendChild(editCommentForm);

      // Append the new comment directly to the container
      const commentsContainer = document.getElementById('commentsContainer');
      if (commentsContainer) {
        commentsContainer.appendChild(commentElement);
      } else {
        console.error('Comments container not found.');
      }

      // Clear the comment input
      document.getElementById('newCommentText').value = '';
    } else {
      console.error('Invalid response structure or missing comment text or author ID.');
    }
  }
}



function showEditRatingForm() {
  document.getElementById('editRatingForm').style.display = 'block';
  document.getElementById('yourRatingP').style.display = 'none';
}







addEventListeners();

function showReportPopUp(){
    const comment_id = event.target.closest('.comment').getAttribute('data-id');
   
  
  document.getElementById('reportCommentId').value = comment_id;

const reportPopUp = document.querySelector('.pop-up-report');
    reportPopUp.style.display = 'block';

    window.onclick = function(event) {
        if (event.target == reportPopUp) {
            reportPopUp.style.display = 'none';
        }
    };

    
  }

  /*function showReportPopUp(commentId) {
    const reportPopUp = document.getElementById(`reportPopUp_${commentId}`);
    const reportCommentIdInput = document.getElementById('reportCommentId');

    // Set the value of the hidden input
    reportCommentIdInput.value = commentId;

    // Display the report pop-up
    reportPopUp.style.display = 'block';

    // Close the pop-up when clicking outside of it
    window.onclick = function(event) {
        if (event.target == reportPopUp) {
            reportPopUp.style.display = 'none';
        }
    };
}*/
/*

const userRatingSubmitButton = document.querySelector('#submit-rating');

if (userRatingSubmitButton) {
  console.log('the button exsuts')
  userRatingSubmitButton.addEventListener('click', addRating);
}


function addRating() {
  event.preventDefault();
 let formData = {
    'event_id': '9',
    'rating': '2',
 };

  sendAjaxRequest('post', '/add-rating', formData, addRatingHandler);
}


function addRatingHandler() {
    console.log("Hi");
}*/


let slideIndex = 1;
showSlides(slideIndex);

// Next/previous controls
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Thumbnail image controls
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
} 


var stripe = Stripe('your-publishable-key');
var elements = stripe.elements();
var card = elements.create('card');

card.mount('#card-element');

card.addEventListener('change', function(event) {
    var displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});




function updateEventCountByMonth() {
  // Obtém o mês atual (você pode personalizar isso conforme necessário)
  let currentMonth = new Date().getMonth() + 1;

  sendAjaxRequest('GET', '/getEventCountByMonth/' + currentMonth, null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('eventCountByMonth').innerText = count;
  });
}

function updateEventCountByDay() {
  // Obtém o dia atual (você pode personalizar isso conforme necessário)
  let currentDay = new Date().getDate();

  sendAjaxRequest('GET', '/getEventCountByDay/' + currentDay, null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('eventCountByDay').innerText = count;
  });
}

function updateEventCountByYear() {
  // Obtém o ano atual (você pode personalizar isso conforme necessário)
  let currentYear = new Date().getFullYear();

  sendAjaxRequest('GET', '/getEventCountByYear/' + currentYear, null, function (event) {
    let count = JSON.parse(event.target.responseText).count;
    document.getElementById('eventCountByYear').innerText = count;
  });
}
