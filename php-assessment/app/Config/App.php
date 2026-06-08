<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Base URL
     */
    public string $baseURL = 'http://localhost:8080/';

    /**
     * Index Page (Empty means index.php is omitted from URLs via rewrite)
     */
    public string $indexPage = '';

    /**
     * URI Protocol
     */
    public string $uriProtocol = 'REQUEST_URI';

    /**
     * Default Locale
     */
    public string $defaultLocale = 'en';

    /**
     * Negotiate Locale
     */
    public bool $negotiateLocale = false;

    /**
     * Supported Locales
     */
    public array $supportedLocales = ['en'];

    /**
     * Timezone
     */
    public string $appTimezone = 'UTC';

    /**
     * Default Character Set
     */
    public string $charset = 'UTF-8';

    /**
     * Force Global HTTPS Secure Requests
     */
    public bool $forceGlobalSecureRequests = false;

    /**
     * Proxy IPs
     */
    public array $proxyIPs = [];
}
