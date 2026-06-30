<?php

namespace Sendzap\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Sendzap\Laravel\SendzapClient;

/**
 * @method static \Sendzap\Laravel\SendzapClient setInstanceId(string $instanceId)
 * @method static array sendText(string $to, string $message, ?string $instanceId = null)
 * @method static array sendMedia(string $to, string $mediaUrl, string $type, ?string $caption = null, ?string $fileName = null, ?string $instanceId = null)
 * @method static array sendBulk(array $messages, ?string $mediaUrl = null, ?string $type = null, ?string $caption = null, ?string $fileName = null, ?int $delay = null, ?string $instanceId = null)
 * @method static array checkNumber(string $number, ?string $instanceId = null)
 * @method static array scheduleText(string $to, string $message, string $scheduledAt, ?string $instanceId = null)
 * @method static array sendContact(string $to, string $contactName, string $contactNumber, ?string $organization = null, ?string $instanceId = null)
 * @method static array sendCarousel(string $to, array $cards, ?string $text = null, ?string $footer = null, ?string $instanceId = null)
 * @method static array sendButtons(string $to, string $text, array $buttons, ?string $footer = null, ?string $instanceId = null)
 * @method static array sendTemplateButtons(string $to, string $text, array $buttons, ?string $footer = null, ?string $imageUrl = null, ?string $instanceId = null)
 * @method static array listInstances()
 * @method static array createInstance(string $name)
 * @method static array showInstance(string $instanceId)
 * @method static array getQr(string $instanceId)
 * @method static array logoutInstance(string $instanceId)
 * @method static array reconnectInstance(string $instanceId)
 * @method static array deleteInstance(string $instanceId)
 * @method static array getGroups(string $instanceId)
 * @method static array getContacts(string $instanceId)
 *
 * @see SendzapClient
 */
class Sendzap extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'sendzap';
    }
}
