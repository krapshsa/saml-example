<?php

namespace App;

use OneLogin\Saml2\Auth;
use OneLogin\Saml2\AuthnRequest;
use OneLogin\Saml2\Settings;
use OneLogin\Saml2\Utils;

class SAMLController
{
    private array $settingsInfo = [
        'sp' => [
            'entityId' => 'ssp',
            'assertionConsumerService' => [
                'url' => 'http://172.16.17.11:9980/acs',
            ],
        ],
        'idp' => [
            'entityId' => 'http://localhost:8080/realms/test',
            'singleSignOnService' => [
                'url' => 'http://localhost:8080/realms/test/protocol/saml',
            ],
            'x509cert' => 'MIIClzCCAX8CBgGNDOFNcjANBgkqhkiG9w0BAQsFADAPMQ0wCwYDVQQDDAR0ZXN0MB4XDTI0MDExNTExMjY0OFoXDTM0MDExNTExMjgyOFowDzENMAsGA1UEAwwEdGVzdDCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAOLQIwaN+gmEEf2NZqWE2FjPblYbtb5O6h8cHmdvSlAyOulq2Nx4a98yVrgnufGlazdqINvkglWFYQwxmWt4Ki4W0rd8fscNRSZDSU55smoyO3RW5Xq92WghiPWbVkYI2IlKcn6OBEi8vbf+78zn/XTsjFlRKKkyNrmqXbnrdlsz07Cm6DB9Z6ZY3I4LEDyA07sgKBGIyJuWKTgKSU8VOP+SYk1wiQxF2LncWhq7BV5ccXJXSVp4KaMngLeTpCTxmYzPb1IezUlNtZSwWoDPnSETjZRUvmZfEzrFgt/F4i9Ehl6qrvzmYdbdHebrCvQoCE6yKddT7gTInL9LSW9YRSkCAwEAATANBgkqhkiG9w0BAQsFAAOCAQEAxzrsggSmeJi4ISu1XJ20KvvqMgyeBjoYxfFk+ax+zgVEFsiUmu1YA3l69VIlrFmXrA0YV4CAAyHUHyhFclhpvTNRaMk5eS9SaXcbMRy7HS0Ishr65Z00LRxnPKM+1t7/kcVnZ0IYwuWshJqaUnm0SfsCRPzrGb4bspm/fm5stuWWP08KQGpGlZA5aW7ECkq+zwDEw71i6v0jmHh6pBsdbpIp/nhHhzmU9KgSIFgpRVpfeOWNXCEHYfzNeEPlnoER5pjIaj/tyt+THjDmFzwNbpq52rm7E1zp81PqCOO6wzR0JfU40ZXbKpiStkfs0i9ak4rvE7ALUvt4m8XUV6mhOw==',
        ],
        'security' => [
            'allowRepeatAttributeName' => true,
        ],
        // If strict mode is Enabled, then the PHP Toolkit will reject unsigned or unencrypted messages if it expects them signed or encrypted
        // Also it will reject the messages if the SAML standard is not strictly followed: Destination, NameID, Conditions ... are validated too.
        'strict' => true,
        // Enable debug mode (to print errors)
        'debug' => true
    ];

    public function sendAuthnRequest()
    {
        // Initialize the settings for the OneLogin toolkit

        try {
            // Create a new AuthRequest and pass the settings
            $settings    = new Settings($this->settingsInfo);
            $authRequest = new AuthnRequest($settings);

            // Get the SAML Request as a deflated, base64 encoded string
            $samlRequest = $authRequest->getRequest();

            // Build the full SSO URL to redirect the user to the IdP for authentication
            // The SAML Request is sent as a GET parameter
            $parameters = ['SAMLRequest' => $samlRequest];
            $url        = Utils::redirect($this->settingsInfo['idp']['singleSignOnService']['url'], $parameters, true);

            // Redirect the user to the IdP for authentication
            header('Location: ' . $url);
        } catch (\Exception $e) {
            // Handle exceptions related to SAML settings
            echo $e->getMessage();
        }

        exit();
    }

    public function handleIDPInitResponse()
    {
        try {
            // Initialize the SAML auth
            $auth = new Auth($this->settingsInfo);

            // Process the SAML response
            $auth->processResponse();

            $errors = $auth->getErrors();

            if (!empty($errors)) {
                throw new \Exception('SAML Response not valid: ' . implode(', ', $errors));
            }

            if (!$auth->isAuthenticated()) {
                throw new \Exception('Not authenticated');
            }

            // User is authenticated, retrieve the user's data
            $userData     = $auth->getAttributes();
            $nameId       = $auth->getNameId();
            $sessionIndex = $auth->getSessionIndex();

            // Do something with the user data, like registering or updating the user in your database
            // ...

            // Redirect to the dashboard or user area
            header('Location: /user/dashboard.php');
            exit();
        } catch (\Exception $e) {
            // Handle exceptions
            echo $e->getMessage();
        }
    }

    public function handleSPInitResponse()
    {
        try {
            // Initialize the SAML auth
            $auth = new Auth($this->settingsInfo);

            // Process the SAML response
            $auth->processResponse();

            $errors = $auth->getErrors();

            if (!empty($errors)) {
                throw new \Exception('SAML Response not valid: ' . implode(', ', $errors));
            }

            if (!$auth->isAuthenticated()) {
                throw new \Exception('Not authenticated');
            }

            // User is authenticated, retrieve the user's data
            $userData     = $auth->getAttributes();
            $nameId       = $auth->getNameId();
            $sessionIndex = $auth->getSessionIndex();

            // Do something with the user data, like registering or updating the user in your database
            // ...

            // Redirect to the dashboard or user area
            header('Location: /user/dashboard.php');
            exit();
        } catch (\Exception $e) {
            // Handle exceptions
            echo $e->getMessage();
        }
    }
}
