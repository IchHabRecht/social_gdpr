import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Notification from "@typo3/backend/notification.js";

class PreviewImageFlush {
    constructor() {
        this.registerClickHandler();
    }

    registerClickHandler() {
        document.querySelectorAll('[data-preview-image-id]').forEach(function (element) {
            element.addEventListener('click', function () {
                const id = element.dataset.previewImageId;
                const type = element.dataset.previewImageType;
                new AjaxRequest(TYPO3.settings.ajaxUrls.preview_image_flush)
                    .post({id, type})
                    .then(async function (response) {
                        const resolved = await response.resolve();
                        Notification.showMessage(resolved.message, '', resolved.status);
                    });
            })
        });
    }
}

export default new PreviewImageFlush;
