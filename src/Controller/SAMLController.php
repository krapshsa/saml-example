<?php

namespace App\Controller;

use App\Config;
use App\UserBackend;
use OneLogin\Saml2\Auth;
use OneLogin\Saml2\AuthnRequest;
use OneLogin\Saml2\Settings;
use OneLogin\Saml2\Utils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class SAMLController
{
    private array $settingsInfo;
    private Session $session;
    private UserBackend $userBackend;

    public function __construct(Config $config, Session $session, UserBackend $userBackend)
    {
        $this->session      = $session;
        $this->settingsInfo = $config->get('saml');
        $this->userBackend  = $userBackend;
    }

    public function sendAuthnRequest(): Response
    {
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
            return new RedirectResponse($url);
        } catch (\Exception $e) {
            // Handle exceptions related to SAML settings
            return new Response($e->getMessage());
        }
    }

    public function handleIDPInitResponse(): Response
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
            if ($this->userBackend->userExists($nameId)) {
                $this->session->set('user', $nameId);
                return new RedirectResponse('/dashboard');
            } else {
                $this->session->set('errmsg', "cannot login with SAML user $nameId, user not exists in backend.");
                return new RedirectResponse('/');
            }
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    public function handleSPInitResponse(): Response
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
            if ($this->userBackend->userExists($nameId)) {
                $this->session->set('user', $nameId);
                return new RedirectResponse('/dashboard');
            } else {
                $this->session->set('errmsg', "cannot login with SAML user $nameId, user not exists in backend.");
                return new RedirectResponse('/');
            }
        } catch (\Exception $e) {
            return new Response($e->getMessage());
        }
    }
}
