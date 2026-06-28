[![Latest Version on Packagist](https://img.shields.io/packagist/v/sendzap/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/sendzap/laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/sendzap/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/sendzap/laravel-sdk)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
# SendZap Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sendzap/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/sendzap/laravel-sdk)
[![Total Downloads](https://img.shields.io/packagist/dt/sendzap/laravel-sdk.svg?style=flat-square)](https://packagist.org/packages/sendzap/laravel-sdk)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A clean and powerful Laravel SDK to interact with the **SendZap WhatsApp API**. Effortlessly manage instances, send text, media, bulk messages, buttons, and carousels directly from your Laravel application.

---

## Installation and Usage

Installation
------------

Install with Composer:

```
composer require sendzap/laravel-sdk
```

Usage
-----

After publishing config and setting `api_key` and `default_instance_id`, use the facade or contract:

```php
use Sendzap\Laravel\Facades\Sendzap;
// or via constructor injection: Sendzap\Laravel\Contracts\SendzapClientContract

Sendzap::sendText('5511999999999', 'Hello world');
```

You can install the package via composer:

```bash
composer require sendzap/laravel-sdk
```

After installing the package, run the installation command to publish the configuration file and set up your environment variables:

```bash
php artisan sendzap:install
```

This will:
1. Publish the `config/sendzap.php` configuration file.
2. Append `SENDZAP_API_KEY`, `SENDZAP_BASE_URL`, and `SENDZAP_DEFAULT_INSTANCE_ID` to your `.env` file.

---

## Configuration

Add your API Key and default Instance ID to your `.env` file:

```env
SENDZAP_API_KEY=your_api_key_here
SENDZAP_BASE_URL=https://api.sendzap.click/api/v1
SENDZAP_DEFAULT_INSTANCE_ID=your_default_whatsapp_instance_uuid
```

---

## Usage

You can use the SDK either via the `Sendzap` Facade or by dependency-injecting the `SendzapClient`.

### 1. Sending Messages

#### Send a Simple Text Message
```php
use Sendzap\Laravel\Facades\Sendzap;

$response = Sendzap::sendText('22990000000', 'Hello from SendZap!');
```

#### Send Media (Image, Video, Document, Audio)
```php
$response = Sendzap::sendMedia(
    to: '22990000000',
    mediaUrl: 'https://example.com/image.png',
    type: 'image',
    caption: 'Check out this image!',
    fileName: 'photo.png' // optional
);
```

#### Send Interactive Buttons
    to: '22990000000',
    text: 'Do you agree with the terms?',
    buttons: [
        [
            'buttonId' => 'yes_id',
            'buttonText' => ['displayText' => 'Yes, I agree']
        ],
        [
            'buttonId' => 'no_id',
            'buttonText' => ['displayText' => 'No']
        ]
    ],
    footer: 'SendZap Interactive Buttons' // optional
);
```

#### Send a Carousel of Cards
```php
$response = Sendzap::sendCarousel(
    to: '22990000000',
    cards: [
        [
            'imageUrl' => 'https://example.com/item1.png',
            'caption' => 'Product 1',
            'footer' => 'Only $10',
            'buttons' => [
                ['type' => 'url', 'displayText' => 'Buy Now', 'url' => 'https://example.com/buy/1']
            ]
        ],
        [
            'imageUrl' => 'https://example.com/item2.png',
            'caption' => 'Product 2',
            'footer' => 'Only $20',
            'buttons' => [
                ['type' => 'url', 'displayText' => 'Buy Now', 'url' => 'https://example.com/buy/2']
            ]
        ]
    ],
    text: 'Check out our new catalog:',
    footer: 'Special offers'
);
```

#### Send Bulk Messages
```php
$response = Sendzap::sendBulk(
    messages: [
        ['to' => '22990000000', 'message' => 'Hello Alice!'],
        ['to' => '22990000001', 'message' => 'Hello Bob!'],
    ],
    delay: 5000 // Delay in ms between messages (default is handled by API)
);
```

---

### 2. Managing Instances

#### List All Instances
```php
$instances = Sendzap::listInstances();
```

#### Create a New Instance
```php
$instance = Sendzap::createInstance('My New WhatsApp Business Instance');
```

#### Get Instance Status and Details
```php
$details = Sendzap::showInstance('instance-uuid-here');
```

#### Get QR Code (Base64) to scan
```php
$qr = Sendzap::getQr('instance-uuid-here');
// returns base64 image data to display in your frontend
```

#### Disconnect / Logout
```php
Sendzap::logoutInstance('instance-uuid-here');
```

---

### 3. Using Multiple Instances Dynamically
If your application manages multiple WhatsApp accounts/instances, you can switch the active instance dynamically on the fly:

```php
// Switch to a specific instance for the next call
Sendzap::setInstanceId('another-instance-uuid')->sendText('22990000000', 'Dynamic instance message!');
```

---

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
