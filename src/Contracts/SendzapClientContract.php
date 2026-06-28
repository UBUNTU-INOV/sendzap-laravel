<?php

namespace Sendzap\Laravel\Contracts;

interface SendzapClientContract
{
    public function setInstanceId(string $instanceId): self;

    public function sendText(string $to, string $message, ?string $instanceId = null): array;

    public function sendMedia(string $to, string $mediaUrl, string $type, ?string $caption = null, ?string $fileName = null, ?string $instanceId = null): array;

    public function sendBulk(array $messages, ?string $mediaUrl = null, ?string $type = null, ?string $caption = null, ?string $fileName = null, ?int $delay = null, ?string $instanceId = null): array;

    public function checkNumber(string $number, ?string $instanceId = null): array;

    public function scheduleText(string $to, string $message, string $scheduledAt, ?string $instanceId = null): array;

    public function sendContact(string $to, string $contactName, string $contactNumber, ?string $organization = null, ?string $instanceId = null): array;

    public function sendCarousel(string $to, array $cards, ?string $text = null, ?string $footer = null, ?string $instanceId = null): array;
}
