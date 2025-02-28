import AjaxRequest from "@typo3/core/ajax/ajax-request.js";
import Notification from "@typo3/backend/notification.js";

class PreviewImageFlush {
  constructor() {
    this.initialize();
  }

  initialize() {
    document.querySelectorAll('[data-preview-image-id]').forEach((element) => {
      element.addEventListener('click', this.handleClick.bind(this));
    });
  }

  async handleClick(event) {
    const element = event.currentTarget;
    const id = element.dataset.previewImageId;
    const type = element.dataset.previewImageType;

    try {
      const response = await new AjaxRequest(TYPO3.settings.ajaxUrls.preview_image_flush)
        .post({id, type});
      const result = await response.resolve();
      Notification.success(result.message, '', 2);
    } catch (error) {
      Notification.error(
        'Error',
        'Error flushing preview image',
        2
      );
      console.error(error);
    }
  }
}

export default new PreviewImageFlush();
