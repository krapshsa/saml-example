<?php

namespace App\Controller;

use App\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SettingsController
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getSAML(): JsonResponse
    {
        $settings = $this->config->get('saml');
        return new JsonResponse($settings);
    }

    public function setSAML(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $this->config->set('saml', $data);

        return new JsonResponse(['status' => 'ok'], Response::HTTP_OK);
    }
}