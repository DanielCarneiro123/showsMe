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

document.addEventListener('DOMContentLoaded', function () {
    function loadEvents(page) {
        sendAjaxRequest('GET', `/myevents-paginate?page=${page}`, null, function () {
            if (this.status >= 200 && this.status < 400) {
                document.getElementById('event-cards-section').innerHTML = this.responseText;
            } else {
                console.error('Failed to load events.');
            }
        });
    }

    document.getElementById('event-cards-section').addEventListener('click', function (e) {
        e.preventDefault();
        if (e.target.tagName === 'A' && e.target.getAttribute('class')=== 'page-link') {
            let page = e.target.getAttribute('href').split('page=')[1];
            loadEvents(page);
        }
    });
});
