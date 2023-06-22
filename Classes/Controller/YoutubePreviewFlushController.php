<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class YoutubePreviewFlushController
{
    public function flush(ServerRequestInterface $serverRequest): ResponseInterface
    {
        $youtubeId = $serverRequest->getParsedBody()['youtubeId'] ?? null;
        if ($youtubeId === null) {
            return new JsonResponse(['status' => AbstractMessage::ERROR, 'message' => 'Missing youtube id']);
        }
        $fileName = 'youtube_' . md5($youtubeId) . '.jpg';
        $absolutePath = GeneralUtility::getFileAbsFileName('typo3temp/assets/tx_socialgdpr/' . $fileName);
        if (!file_exists($absolutePath)) {
            return new JsonResponse(['status' => AbstractMessage::INFO, 'message' => 'Preview image does not exist']);
        }
        $result = unlink($absolutePath);
        if ($result === false) {
            return new JsonResponse(['status' => AbstractMessage::ERROR, 'message' => 'Something went wrong']);
        }
        return new JsonResponse(['status' => AbstractMessage::OK, 'message' => 'Preview image deleted']);
    }
}
