document.addEventListener('DOMContentLoaded', function() {
    var iframes = document.querySelectorAll('.social-gdpr-youtube-video, .social-gdpr-vimeo-video, .social-gdpr-google-maps, .social-gdpr-osm-map');
    iframes.forEach(function(iframe) {
        iframe.addEventListener('click', function(event) {
            if (event.target.tagName.toLowerCase() !== 'a') {
                var iframe = event.currentTarget;
                iframe.parentNode.innerHTML = atob(iframe.children[0].getAttribute('data-iframe'));
                event.preventDefault();
            }
        });
    });
});
