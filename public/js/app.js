function addEventListeners() {
    
  }
  
  function encodeForAjax(data) {
    if (data == null) return null;
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&');
  }
  

  function updatePageContent(formData, ticketTypeId) {
    document.getElementById('new_stock_' + ticketTypeId ).innerHTML = formData['new_stock_' + ticketTypeId];
    document.getElementById('stock_display_' + ticketTypeId ).innerHTML = 'Stock: ' + formData['new_stock_' + ticketTypeId];
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
            updatePageContent(response, ticketTypeId);
        } else {
            console.error('Error updating stock:', this.responseText);
        }
    });
}


  
  addEventListeners();

  
  