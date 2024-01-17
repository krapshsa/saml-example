<?php

namespace App;

class Config
{
    private string $jsonFile;
    private array $settings;

    public function __construct(string $jsonFile)
    {
        $this->jsonFile = $jsonFile;

        if (file_exists($this->jsonFile)) {
            $this->settings = json_decode(file_get_contents($this->jsonFile), true);
        } else {
            $this->settings = [];
        }
    }

    public function get(string $keyword): mixed
    {
        return $this->settings[$keyword] ?? null;
    }

    public function set(string $keyword, mixed $value): void
    {
        $this->settings[$keyword] = $value;
        file_put_contents($this->jsonFile, json_encode($this->settings));
    }
}