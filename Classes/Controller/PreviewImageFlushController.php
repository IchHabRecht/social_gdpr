<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Controller;

use IchHabRecht\SocialGdpr\Service\PreviewImageServiceRegistry;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PreviewImageFlushController
{
    public function flush(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $youtubeId = $serverRequest->getParsedBody()['id'] ?? null;
        $type = $serverRequest->getParsedBody()['type'] ?? null;
        if ($youtubeId === null) {
            return new JsonResponse(['status' => AbstractMessage::ERROR, 'message' => 'Missing id']);
        }
        if ($type === null) {
            return new JsonResponse(['status' => AbstractMessage::ERROR, 'message' => 'Missing type']);
        }

        $previewImageServiceRegistry = GeneralUtility::makeInstance(PreviewImageServiceRegistry::class);
        $previewImageService = $previewImageServiceRegistry->getPreviewImageService($type);

        if (!$previewImageService->hasPreviewImage($youtubeId)) {
            return new JsonResponse(['status' => AbstractMessage::INFO, 'message' => 'Preview image does not exist']);
        }
        $previewImageService->deletePreviewImage($youtubeId);

        return new JsonResponse(['status' => AbstractMessage::OK, 'message' => 'Preview image deleted']);
    }
}
