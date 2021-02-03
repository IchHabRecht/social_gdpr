document.addEventListener('DOMContentLoaded', function() {
    var iframes = document.querySelectorAll('.social-gdpr-youtube-video, .social-gdpr-vimeo-video, .social-gdpr-google-maps, .social-gdpr-osm-map');
    iframes.forEach(function(iframe) {
        ['click', 'keydown'].forEach(function (e) {
            iframe.addEventListener(e, function(event) {
                // load on enter keydown, spacebar keydown and click
                if (event.keyCode === 13 || event.keyCode === 32 || e === 'click') {
                    if (event.target.tagName.toLowerCase() !== 'a') {
                        var iframe = event.currentTarget;
                        iframe.parentNode.innerHTML = atob(iframe.children[0].getAttribute('data-iframe'));
                        event.preventDefault();
                    }
                }
            });
        });
    });
});
