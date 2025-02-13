<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $name;

    public string $email;

    public string $about;

    public string $phone;

    public string $address;

    public string $post;

    public ?string $logo;

    public static function group(): string
    {
        return 'app';
    }
}
