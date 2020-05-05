document.addEventListener('DOMContentLoaded', function() {
	var videos = document.querySelectorAll('.social-gdpr-youtube-video, .social-gdpr-vimeo-video');
	videos.forEach(function(video) {
		video.addEventListener('click', function(event) {
			if (event.target.tagName.toLowerCase() !== 'a') {
				var video = event.currentTarget;
				video.parentNode.innerHTML = atob(video.children[0].getAttribute('data-iframe'));
				event.preventDefault();
			}
		})
	})
});
