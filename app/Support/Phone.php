<?php

namespace App\Support;

class Phone
{
    /** Digits only, in international form, ready for tel: and wa.me links. */
    public static function international(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        // 00 is the international prefix used in place of a leading +.
        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }

        // Already international (stored with a leading + or the country prefix).
        $country = (string) config('app.country_code');

        if (str_starts_with((string) $phone, '+') || str_starts_with($digits, $country)) {
            return $digits;
        }

        return $country.ltrim($digits, '0');
    }
}
