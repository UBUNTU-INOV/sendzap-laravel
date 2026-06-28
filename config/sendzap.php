<?php

return [
    /*
     * The API Key used to authenticate with the SendZap API.
     * You can generate this key in the SendZap Developer Portal.
     */
    'api_key' => env('SENDZAP_API_KEY'),

    /*
     * The base URL for the SendZap API.
     */
    'base_url' => env('SENDZAP_BASE_URL', 'https://api.sendzap.click/api/v1'),

    /*
     * Default WhatsApp Instance ID to use if none is specified.
     */
    'default_instance_id' => env('SENDZAP_DEFAULT_INSTANCE_ID'),
];
