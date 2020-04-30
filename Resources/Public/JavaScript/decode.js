document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('.social-gdpr-youtube-video, .social-gdpr-vimeo-video');
    videos.forEach(video => video.addEventListener('click', event => {
        if (event.target.tagName.toLowerCase() !== 'a') {
            const video = event.currentTarget;
            video.parentNode.innerHTML = atob(video.children[0].getAttribute('data-iframe'));
            event.preventDefault();
        }
    }))
});
