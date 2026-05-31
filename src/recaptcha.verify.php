<?php

////	Recaptcha Verify
// Check if Recaptcha response is valid.
// https://developers.google.com/recaptcha/docs/verify
// curl POST https://www.google.com/recaptcha/api/siteverify

/**
 * Verify a Google reCAPTCHA v2 response token via the siteverify API.
 *
 * @param string       $RecaptchaSecret Your reCAPTCHA secret key.
 * @param string|null  $Response        The g-recaptcha-response token from the form.
 * @param string|false $UserIP          Optional end-user IP address.
 * @param bool         $Debug           When true, dump the cURL info and decoded response.
 * @param string       $Endpoint        reCAPTCHA siteverify endpoint; override only for testing.
 *
 * @return array<string, mixed> The decoded API response with an added 'Success' boolean, or an error array.
 */
function Recaptcha_Verify(string $RecaptchaSecret, ?string $Response, $UserIP = false, bool $Debug = false, string $Endpoint = 'https://www.google.com/recaptcha/api/siteverify'): array
{

    $Check = curl_init();

    curl_setopt($Check, CURLOPT_URL, $Endpoint);
    curl_setopt($Check, CURLOPT_RETURNTRANSFER, true);
    curl_setopt_array($Check, [
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => [
            'secret' => $RecaptchaSecret,
            'response' => $Response,
            'remoteip' => $UserIP,
        ],
    ]);

    $RawResponse = curl_exec($Check);
    $CheckError = curl_errno($Check);
    $CheckErrorMessage = curl_error($Check);
    $Info = curl_getinfo($Check);

    $Decoded = is_string($RawResponse) ? json_decode($RawResponse, true) : null;

    if ($Debug) {
        echo '$Info is ';
        var_dump($Info);
        echo PHP_EOL;
        echo '$Response is ';
        var_dump($Decoded);
        echo PHP_EOL;
    }

    if ($CheckError) {
        return ['Success' => false, 'Error' => $CheckError . ' Error: ' . $CheckErrorMessage];
    }

    if (! is_array($Decoded)) {
        return ['Success' => false, 'Error' => 'Invalid response from reCAPTCHA server.'];
    }

    $Decoded['Success'] = ! empty($Decoded['success']);

    return $Decoded;
}
