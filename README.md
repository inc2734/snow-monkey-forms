# Snow Monkey Forms

The Snow Monkey Forms is a mail form plugin for the block editor.

## Features

### Cloudflare Turnstile Support

Snow Monkey Forms now supports Cloudflare Turnstile for spam protection. Turnstile is a CAPTCHA alternative that protects forms from fraud and abuse without slowing down web experiences for real users.

To enable Turnstile:

1. Get your Site Key and Secret Key from [Cloudflare Turnstile dashboard](https://dash.cloudflare.com/?to=/:account/turnstile)
2. In your WordPress admin, go to **Snow Monkey Forms > Turnstile**
3. Enter your Site Key and Secret Key
4. Check "Auto add to forms" to automatically add Turnstile to all forms, or manually add the `cf-turnstile` div to specific forms

### reCAPTCHA Support

Snow Monkey Forms also supports Google reCAPTCHA v3 for additional spam protection options.

## Build

```
$ composer install
$ npm install
$ npm run build
```
