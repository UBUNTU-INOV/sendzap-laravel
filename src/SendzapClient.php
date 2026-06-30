<?php

namespace Sendzap\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Sendzap\Laravel\Contracts\SendzapClientContract;
use Sendzap\Laravel\Exceptions\ApiException;
use Sendzap\Laravel\Exceptions\MissingInstanceIdException;

class SendzapClient implements SendzapClientContract
{
    protected string $apiKey;

    protected string $baseUrl;

    protected ?string $defaultInstanceId;

    protected Client $httpClient;

    public function __construct(string $apiKey, string $baseUrl, ?string $defaultInstanceId = null, ?Client $httpClient = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->defaultInstanceId = $defaultInstanceId;

        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => $this->baseUrl.'/',
            'headers' => [
                'Authorization' => 'Bearer '.$this->apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Set a temporary instance ID for the next request.
     */
    public function setInstanceId(string $instanceId): self
    {
        $this->defaultInstanceId = $instanceId;

        return $this;
    }

    /**
     * Get the instance ID to use.
     */
    protected function getInstanceId(?string $instanceId = null): string
    {
        $id = $instanceId ?? $this->defaultInstanceId;

        if (! $id) {
            throw new MissingInstanceIdException('No WhatsApp Instance ID provided. Set it in config or pass it as an argument.');
        }

        return $id;
    }

    /**
     * Send a request.
     */
    protected function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $uri, $options);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $body = json_decode($response->getBody()->getContents(), true);
                throw new ApiException($body['message'] ?? $e->getMessage(), $response->getStatusCode());
            }
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    /* -------------------------------------------------------------------------- */
    /*                              MESSAGING API */
    /* -------------------------------------------------------------------------- */

    /**
     * Send a simple text message.
     */
    public function sendText(string $to, string $message, ?string $instanceId = null): array
    {
        return $this->request('POST', 'send-message', [
            'json' => [
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'message' => $message,
            ],
        ]);
    }

    /**
     * Send a media message.
     */
    public function sendMedia(
        string $to,
        string $mediaUrl,
        string $type,
        ?string $caption = null,
        ?string $fileName = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-media', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'media_url' => $mediaUrl,
                'type' => $type,
                'caption' => $caption,
                'file_name' => $fileName,
            ]),
        ]);
    }

    /**
     * Send bulk messages.
     */
    public function sendBulk(
        array $messages,
        ?string $mediaUrl = null,
        ?string $type = null,
        ?string $caption = null,
        ?string $fileName = null,
        ?int $delay = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-bulk', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'messages' => $messages,
                'media_url' => $mediaUrl,
                'type' => $type,
                'caption' => $caption,
                'file_name' => $fileName,
                'delay' => $delay,
            ]),
        ]);
    }

    /**
     * Check if a number is registered on WhatsApp.
     */
    public function checkNumber(string $number, ?string $instanceId = null): array
    {
        return $this->request('POST', 'check-number', [
            'json' => array_filter([
                'instance_id' => $instanceId ?? $this->defaultInstanceId,
                'number' => $number,
            ]),
        ]);
    }

    /**
     * Schedule a message for later.
     */
    public function scheduleText(string $to, string $message, string $scheduledAt, ?string $instanceId = null): array
    {
        return $this->request('POST', 'schedule-message', [
            'json' => [
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'message' => $message,
                'scheduled_at' => $scheduledAt,
            ],
        ]);
    }

    /**
     * Send a contact (VCard).
     */
    public function sendContact(
        string $to,
        string $contactName,
        string $contactNumber,
        ?string $organization = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-contact', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'contact_name' => $contactName,
                'contact_number' => $contactNumber,
                'organization' => $organization,
            ]),
        ]);
    }

    /**
     * Send a carousel of cards.
     */
    public function sendCarousel(
        string $to,
        array $cards,
        ?string $text = null,
        ?string $footer = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-carousel', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'cards' => $cards,
                'text' => $text,
                'footer' => $footer,
            ]),
        ]);
    }

    /**
     * Send interactive buttons.
     */
    public function sendButtons(
        string $to,
        string $text,
        array $buttons,
        ?string $footer = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-buttons', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'text' => $text,
                'buttons' => $buttons,
                'footer' => $footer,
            ]),
        ]);
    }

    /**
     * Send template buttons.
     */
    public function sendTemplateButtons(
        string $to,
        string $text,
        array $buttons,
        ?string $footer = null,
        ?string $imageUrl = null,
        ?string $instanceId = null
    ): array {
        return $this->request('POST', 'send-template-buttons', [
            'json' => array_filter([
                'instance_id' => $this->getInstanceId($instanceId),
                'to' => $to,
                'text' => $text,
                'buttons' => $buttons,
                'footer' => $footer,
                'image_url' => $imageUrl,
            ]),
        ]);
    }

    /* -------------------------------------------------------------------------- */
    /*                              INSTANCES API */
    /* -------------------------------------------------------------------------- */

    /**
     * List all instances.
     */
    public function listInstances(): array
    {
        return $this->request('GET', 'instances');
    }

    /**
     * Create a new instance.
     */
    public function createInstance(string $name): array
    {
        return $this->request('POST', 'instances', [
            'json' => [
                'name' => $name,
            ],
        ]);
    }

    /**
     * Show details of a specific instance.
     */
    public function showInstance(string $instanceId): array
    {
        return $this->request('GET', "instances/{$instanceId}");
    }

    /**
     * Get the QR code base64 of an instance.
     */
    public function getQr(string $instanceId): array
    {
        return $this->request('GET', "instances/{$instanceId}/qr");
    }

    /**
     * Logout / disconnect an instance.
     */
    public function logoutInstance(string $instanceId): array
    {
        return $this->request('POST', "instances/{$instanceId}/logout");
    }

    /**
     * Reconnect / restart an instance.
     */
    public function reconnectInstance(string $instanceId): array
    {
        return $this->request('POST', "instances/{$instanceId}/reconnect");
    }

    /**
     * Delete an instance.
     */
    public function deleteInstance(string $instanceId): array
    {
        return $this->request('DELETE', "instances/{$instanceId}");
    }

    /**
     * Get all groups of an instance.
     */
    public function getGroups(string $instanceId): array
    {
        return $this->request('GET', "instances/{$instanceId}/groups");
    }

    /**
     * Get all contacts of an instance.
     */
    public function getContacts(string $instanceId): array
    {
        return $this->request('GET', "instances/{$instanceId}/contacts");
    }
}
