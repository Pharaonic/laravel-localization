<?php

return [

    /**
     *  You can specify how your URLs can be localizable.
     *
     *  1. ccTLDs
     *  2. sub-domain
     *  3. sub-directory (default)
     */
    'type'              => 'sub-directory',


    /**
     *  Default Locale
     *  Used with types [sub-domain, sub-directory]
     */
    'default'           => 'en',


    /**
     *  Allow to hide the default locale.
     *  Used with types [sub-domain, sub-directory]
     *
     *  default : false
     *  If you made it `true` that means it gonna be redirect to the same URL wihtout locale if the current locale == default
     */
    'hide-default'      => false,


    /**
     *  Force redirect to default localization If there's no locale.
     *  Used with hide-default == false
     *
     *  default : true
     */
    'force-redirect'      => true,


    /**
     *  ONLY supported locales list.
     *  You can customize it as you want, but it should be locales codes already exists in Locales List.
     */
    'supported'         => ['ar', 'en'],


    /**
     *  List of all locales (supported and not).
     *
     *  code => name, native, abbreviation and direction (RTL).
     */
    'list'              => [
        'ar' => ['name' => 'Arabic',    'native' => 'العربية',  'abbr' => '؏', 'rtl' => true],
        'en' => ['name' => 'English',   'native' => 'English',  'abbr' => 'EN', 'rtl' => false],
        'es' => ['name' => 'Spanish',   'native' => 'Español',  'abbr' => 'ES', 'rtl' => false],
        'fr' => ['name' => 'French',    'native' => 'Français', 'abbr' => 'FR', 'rtl' => false],
        'ru' => ['name' => 'Russian',   'native' => 'русский',  'abbr' => 'RU', 'rtl' => false],
        'zh' => ['name' => 'Chinese',   'native' => '中文',     'abbr' => 'ZH', 'rtl' => false],
    ],


    /**
     *  List of all ccTLDs.
     *
     *  Used with type [ccTLD]
     */
    'ccTLDs-list'       => [
        'eg' => 'ar',
        'us' => 'en',
    ],
];
