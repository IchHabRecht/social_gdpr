<?php

declare(strict_types=1);

namespace IchHabRecht\SocialGdpr\Service;

interface PreviewImageServiceInterface
{
    public function getPreviewImage(string $id) : string;

    public function deletePreviewImage(string $id) : void;

    public function hasPreviewImage(string $id) : bool;
}
