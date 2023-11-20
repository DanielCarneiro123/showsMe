function addEventListeners() {
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

  function like(postId) {
    let post = document.querySelector('#post' + postId);
    let likeCounter = post.querySelector('.qtd-likes').innerText;
    let likeButton = post.querySelector('.button-like');

    // Update like counter
    post.querySelector('.qtd-likes').innerText = parseInt(likeCounter) + 1;

    // Send server request
    sendAjaxRequest('post', '../post/like', {id: postId});

    // Remove like button
    likeButton.remove();
}

  function updatePageContent(formData) {
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

      //document.getElementById('location').innerHTML = 'Location: ' + formData.edit_location;
      updatePageContent(formData)

      sendAjaxRequest('post', '../update-event/' + eventId, formData);

      //colocar uma mensagem a dizer que foi alterado
  }


addEventListeners();
