define([
    'TYPO3/CMS/Core/Ajax/AjaxRequest',
    'TYPO3/CMS/Backend/Notification',
], function (AjaxRequest, Notification) {
    document.querySelectorAll('[data-youtube-id]').forEach(function (element) {
        element.addEventListener('click', function () {
            const youtubeId = element.dataset.youtubeId;
            new AjaxRequest(TYPO3.settings.ajaxUrls.youtube_preview_flush)
                .post({youtubeId: youtubeId})
                .then(async function (response) {
                    const resolved = await response.resolve();
                    Notification.showMessage(resolved.message, '', resolved.status);
                });
        })
    });
});
