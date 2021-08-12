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
     *  List of all ccTLDs.
     *
     *  Used with type [ccTLD]
     */
    'ccTLDs-list'       => [
        'eg' => 'ar',
        'us' => 'en',
    ],


    /**
     *  List of all locales (supported and not).
     *
     *  code => name, native, abbreviation and direction (RTL).
     */
    'list'              => [














        'af'            => [
            'name'   => 'Afrikaans',
            'native' => 'Afrikaans',
            'rtl'    => false
        ],
        'af_NA'         => [
            'name'   => 'Afrikaans (Namibia)',
            'native' => 'Afrikaans',
            'rtl'    => false
        ],
        'af_ZA'         => [
            'name'   => 'Afrikaans (South Africa)',
            'native' => 'Afrikaans',
            'rtl'    => false
        ],
        'ak'            => [
            'name'   => 'Akan',
            'native' => '',
            'rtl'    => false
        ],
        'ak_GH'         => [
            'name'   => 'Akan (Ghana)',
            'native' => '',
            'rtl'    => false
        ],
        'sq'            => [
            'name'   => 'Albanian',
            'native' => 'shqip',
            'rtl'    => false
        ],
        'sq_AL'         => [
            'name'   => 'Albanian (Albania)',
            'native' => 'shqip (Shqipëri)',
            'rtl'    => false
        ],
        'sq_XK'         => [
            'name'   => 'Albanian (Kosovo)',
            'native' => 'shqip (Kosovë)',
            'rtl'    => false
        ],
        'sq_MK'         => [
            'name'   => 'Albanian (Macedonia)',
            'native' => 'shqip (Maqedoni)',
            'rtl'    => false
        ],
        'am'            => [
            'name'   => 'Amharic',
            'native' => '',
            'rtl'    => false
        ],
        'am_ET'         => [
            'name'   => 'Amharic (Ethiopia)',
            'native' => '',
            'rtl'    => false
        ],
        'ar'            => [
            'name'   => 'Arabic',
            'native' => '',
            'rtl'    => false
        ],
        'ar_DZ'         => [
            'name'   => 'Arabic (Algeria)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_BH'         => [
            'name'   => 'Arabic (Bahrain)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_TD'         => [
            'name'   => 'Arabic (Chad)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_KM'         => [
            'name'   => 'Arabic (Comoros)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_DJ'         => [
            'name'   => 'Arabic (Djibouti)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_EG'         => [
            'name'   => 'Arabic (Egypt)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_ER'         => [
            'name'   => 'Arabic (Eritrea)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_IQ'         => [
            'name'   => 'Arabic (Iraq)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_IL'         => [
            'name'   => 'Arabic (Israel)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_JO'         => [
            'name'   => 'Arabic (Jordan)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_KW'         => [
            'name'   => 'Arabic (Kuwait)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_LB'         => [
            'name'   => 'Arabic (Lebanon)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_LY'         => [
            'name'   => 'Arabic (Libya)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_MR'         => [
            'name'   => 'Arabic (Mauritania)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_MA'         => [
            'name'   => 'Arabic (Morocco)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_OM'         => [
            'name'   => 'Arabic (Oman)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_PS'         => [
            'name'   => 'Arabic (Palestinian Territories)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_QA'         => [
            'name'   => 'Arabic (Qatar)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_SA'         => [
            'name'   => 'Arabic (Saudi Arabia)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_SO'         => [
            'name'   => 'Arabic (Somalia)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_SS'         => [
            'name'   => 'Arabic (South Sudan)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_SD'         => [
            'name'   => 'Arabic (Sudan)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_SY'         => [
            'name'   => 'Arabic (Syria)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_TN'         => [
            'name'   => 'Arabic (Tunisia)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_AE'         => [
            'name'   => 'Arabic (United Arab Emirates)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_EH'         => [
            'name'   => 'Arabic (Western Sahara)',
            'native' => '',
            'rtl'    => false
        ],
        'ar_YE'         => [
            'name'   => 'Arabic (Yemen)',
            'native' => '',
            'rtl'    => false
        ],
        'hy'            => [
            'name'   => 'Armenian',
            'native' => '',
            'rtl'    => false
        ],
        'hy_AM'         => [
            'name'   => 'Armenian (Armenia)',
            'native' => '',
            'rtl'    => false
        ],
        'as'            => [
            'name'   => 'Assamese',
            'native' => '',
            'rtl'    => false
        ],
        'as_IN'         => [
            'name'   => 'Assamese (India)',
            'native' => '',
            'rtl'    => false
        ],
        'az'            => [
            'name'   => 'Azerbaijani',
            'native' => '',
            'rtl'    => false
        ],
        'az_AZ'         => [
            'name'   => 'Azerbaijani (Azerbaijan)',
            'native' => '',
            'rtl'    => false
        ],
        'az_Cyrl_AZ'    => [
            'name'   => 'Azerbaijani (Cyrillic, Azerbaijan)',
            'native' => '',
            'rtl'    => false
        ],
        'az_Cyrl'       => [
            'name'   => 'Azerbaijani (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'az_Latn_AZ'    => [
            'name'   => 'Azerbaijani (Latin, Azerbaijan)',
            'native' => '',
            'rtl'    => false
        ],
        'az_Latn'       => [
            'name'   => 'Azerbaijani (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'bm'            => [
            'name'   => 'Bambara',
            'native' => '',
            'rtl'    => false
        ],
        'bm_Latn_ML'    => [
            'name'   => 'Bambara (Latin, Mali)',
            'native' => '',
            'rtl'    => false
        ],
        'bm_Latn'       => [
            'name'   => 'Bambara (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'eu'            => [
            'name'   => 'Basque',
            'native' => '',
            'rtl'    => false
        ],
        'eu_ES'         => [
            'name'   => 'Basque (Spain)',
            'native' => '',
            'rtl'    => false
        ],
        'be'            => [
            'name'   => 'Belarusian',
            'native' => '',
            'rtl'    => false
        ],
        'be_BY'         => [
            'name'   => 'Belarusian (Belarus)',
            'native' => '',
            'rtl'    => false
        ],
        'bn'            => [
            'name'   => 'Bengali',
            'native' => '',
            'rtl'    => false
        ],
        'bn_BD'         => [
            'name'   => 'Bengali (Bangladesh)',
            'native' => '',
            'rtl'    => false
        ],
        'bn_IN'         => [
            'name'   => 'Bengali (India)',
            'native' => '',
            'rtl'    => false
        ],
        'bs'            => [
            'name'   => 'Bosnian',
            'native' => '',
            'rtl'    => false
        ],
        'bs_BA'         => [
            'name'   => 'Bosnian (Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'bs_Cyrl_BA'    => [
            'name'   => 'Bosnian (Cyrillic, Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'bs_Cyrl'       => [
            'name'   => 'Bosnian (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'bs_Latn_BA'    => [
            'name'   => 'Bosnian (Latin, Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'bs_Latn'       => [
            'name'   => 'Bosnian (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'br'            => [
            'name'   => 'Breton',
            'native' => '',
            'rtl'    => false
        ],
        'br_FR'         => [
            'name'   => 'Breton (France)',
            'native' => '',
            'rtl'    => false
        ],
        'bg'            => [
            'name'   => 'Bulgarian',
            'native' => '',
            'rtl'    => false
        ],
        'bg_BG'         => [
            'name'   => 'Bulgarian (Bulgaria)',
            'native' => '',
            'rtl'    => false
        ],
        'my'            => [
            'name'   => 'Burmese',
            'native' => '',
            'rtl'    => false
        ],
        'my_MM'         => [
            'name'   => 'Burmese (Myanmar (Burma))',
            'native' => '',
            'rtl'    => false
        ],
        'ca'            => [
            'name'   => 'Catalan',
            'native' => '',
            'rtl'    => false
        ],
        'ca_AD'         => [
            'name'   => 'Catalan (Andorra)',
            'native' => '',
            'rtl'    => false
        ],
        'ca_FR'         => [
            'name'   => 'Catalan (France)',
            'native' => '',
            'rtl'    => false
        ],
        'ca_IT'         => [
            'name'   => 'Catalan (Italy)',
            'native' => '',
            'rtl'    => false
        ],
        'ca_ES'         => [
            'name'   => 'Catalan (Spain)',
            'native' => '',
            'rtl'    => false
        ],
        'zh'            => [
            'name'   => 'Chinese',
            'native' => '',
            'rtl'    => false
        ],
        'zh_CN'         => [
            'name'   => 'Chinese (China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_HK'         => [
            'name'   => 'Chinese (Hong Kong SAR China)',
            'native' => '中文',
            'rtl'    => false
        ],
        'zh_MO'         => [
            'name'   => 'Chinese (Macau SAR China)',
            'native' => '中文',
            'rtl'    => false
        ],
        'zh_Hans_CN'    => [
            'name'   => 'Chinese (Simplified, China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hans_HK'    => [
            'name'   => 'Chinese (Simplified, Hong Kong SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hans_MO'    => [
            'name'   => 'Chinese (Simplified, Macau SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hans_SG'    => [
            'name'   => 'Chinese (Simplified, Singapore)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hans'       => [
            'name'   => 'Chinese (Simplified)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_SG'         => [
            'name'   => 'Chinese (Singapore)',
            'native' => '中文',
            'rtl'    => false
        ],
        'zh_TW'         => [
            'name'   => 'Chinese (Taiwan)',
            'native' => '中文',
            'rtl'    => false
        ],
        'zh_Hant_HK'    => [
            'name'   => 'Chinese (Traditional, Hong Kong SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hant_MO'    => [
            'name'   => 'Chinese (Traditional, Macau SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'zh_Hant_TW'    => [
            'name'   => 'Chinese (Traditional, Taiwan)',
            'native' => '繁體中文',
            'rtl'    => false
        ],
        'zh_Hant'       => [
            'name'   => 'Chinese (Traditional)',
            'native' => '',
            'rtl'    => false
        ],
        'kw'            => [
            'name'   => 'Cornish',
            'native' => '',
            'rtl'    => false
        ],
        'kw_GB'         => [
            'name'   => 'Cornish (United Kingdom)',
            'native' => '',
            'rtl'    => false
        ],
        'hr'            => [
            'name'   => 'Croatian',
            'native' => '',
            'rtl'    => false
        ],
        'hr_BA'         => [
            'name'   => 'Croatian (Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'hr_HR'         => [
            'name'   => 'Croatian (Croatia)',
            'native' => '',
            'rtl'    => false
        ],
        'cs'            => [
            'name'   => 'Czech',
            'native' => '',
            'rtl'    => false
        ],
        'cs_CZ'         => [
            'name'   => 'Czech (Czech Republic)',
            'native' => '',
            'rtl'    => false
        ],
        'da'            => [
            'name'   => 'Danish',
            'native' => '',
            'rtl'    => false
        ],
        'da_DK'         => [
            'name'   => 'Danish (Denmark)',
            'native' => '',
            'rtl'    => false
        ],
        'da_GL'         => [
            'name'   => 'Danish (Greenland)',
            'native' => '',
            'rtl'    => false
        ],
        'nl'            => [
            'name'   => 'Dutch',
            'native' => '',
            'rtl'    => false
        ],
        'nl_AW'         => [
            'name'   => 'Dutch (Aruba)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_BE'         => [
            'name'   => 'Dutch (Belgium)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_BQ'         => [
            'name'   => 'Dutch (Caribbean Netherlands)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_CW'         => [
            'name'   => 'Dutch (Curaçao)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_NL'         => [
            'name'   => 'Dutch (Netherlands)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_SX'         => [
            'name'   => 'Dutch (Sint Maarten)',
            'native' => '',
            'rtl'    => false
        ],
        'nl_SR'         => [
            'name'   => 'Dutch (Suriname)',
            'native' => '',
            'rtl'    => false
        ],
        'dz'            => [
            'name'   => 'Dzongkha',
            'native' => '',
            'rtl'    => false
        ],
        'dz_BT'         => [
            'name'   => 'Dzongkha (Bhutan)',
            'native' => '',
            'rtl'    => false
        ],
        'en'            => [
            'name'   => 'English',
            'native' => '',
            'rtl'    => false
        ],
        'en_AS'         => [
            'name'   => 'English (American Samoa)',
            'native' => '',
            'rtl'    => false
        ],
        'en_AI'         => [
            'name'   => 'English (Anguilla)',
            'native' => '',
            'rtl'    => false
        ],
        'en_AG'         => [
            'name'   => 'English (Antigua & Barbuda)',
            'native' => '',
            'rtl'    => false
        ],
        'en_AU'         => [
            'name'   => 'English (Australia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BS'         => [
            'name'   => 'English (Bahamas)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BB'         => [
            'name'   => 'English (Barbados)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BE'         => [
            'name'   => 'English (Belgium)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BZ'         => [
            'name'   => 'English (Belize)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BM'         => [
            'name'   => 'English (Bermuda)',
            'native' => '',
            'rtl'    => false
        ],
        'en_BW'         => [
            'name'   => 'English (Botswana)',
            'native' => '',
            'rtl'    => false
        ],
        'en_IO'         => [
            'name'   => 'English (British Indian Ocean Territory)',
            'native' => '',
            'rtl'    => false
        ],
        'en_VG'         => [
            'name'   => 'English (British Virgin Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_CM'         => [
            'name'   => 'English (Cameroon)',
            'native' => '',
            'rtl'    => false
        ],
        'en_CA'         => [
            'name'   => 'English (Canada)',
            'native' => '',
            'rtl'    => false
        ],
        'en_KY'         => [
            'name'   => 'English (Cayman Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_CX'         => [
            'name'   => 'English (Christmas Island)',
            'native' => '',
            'rtl'    => false
        ],
        'en_CC'         => [
            'name'   => 'English (Cocos (Keeling) Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_CK'         => [
            'name'   => 'English (Cook Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_DG'         => [
            'name'   => 'English (Diego Garcia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_DM'         => [
            'name'   => 'English (Dominica)',
            'native' => '',
            'rtl'    => false
        ],
        'en_ER'         => [
            'name'   => 'English (Eritrea)',
            'native' => '',
            'rtl'    => false
        ],
        'en_FK'         => [
            'name'   => 'English (Falkland Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_FJ'         => [
            'name'   => 'English (Fiji)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GM'         => [
            'name'   => 'English (Gambia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GH'         => [
            'name'   => 'English (Ghana)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GI'         => [
            'name'   => 'English (Gibraltar)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GD'         => [
            'name'   => 'English (Grenada)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GU'         => [
            'name'   => 'English (Guam)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GG'         => [
            'name'   => 'English (Guernsey)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GY'         => [
            'name'   => 'English (Guyana)',
            'native' => '',
            'rtl'    => false
        ],
        'en_HK'         => [
            'name'   => 'English (Hong Kong SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'en_IN'         => [
            'name'   => 'English (India)',
            'native' => '',
            'rtl'    => false
        ],
        'en_IE'         => [
            'name'   => 'English (Ireland)',
            'native' => '',
            'rtl'    => false
        ],
        'en_IM'         => [
            'name'   => 'English (Isle of Man)',
            'native' => '',
            'rtl'    => false
        ],
        'en_JM'         => [
            'name'   => 'English (Jamaica)',
            'native' => '',
            'rtl'    => false
        ],
        'en_JE'         => [
            'name'   => 'English (Jersey)',
            'native' => '',
            'rtl'    => false
        ],
        'en_KE'         => [
            'name'   => 'English (Kenya)',
            'native' => '',
            'rtl'    => false
        ],
        'en_KI'         => [
            'name'   => 'English (Kiribati)',
            'native' => '',
            'rtl'    => false
        ],
        'en_LS'         => [
            'name'   => 'English (Lesotho)',
            'native' => '',
            'rtl'    => false
        ],
        'en_LR'         => [
            'name'   => 'English (Liberia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MO'         => [
            'name'   => 'English (Macau SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MG'         => [
            'name'   => 'English (Madagascar)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MW'         => [
            'name'   => 'English (Malawi)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MY'         => [
            'name'   => 'English (Malaysia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MT'         => [
            'name'   => 'English (Malta)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MH'         => [
            'name'   => 'English (Marshall Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MU'         => [
            'name'   => 'English (Mauritius)',
            'native' => '',
            'rtl'    => false
        ],
        'en_FM'         => [
            'name'   => 'English (Micronesia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MS'         => [
            'name'   => 'English (Montserrat)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NA'         => [
            'name'   => 'English (Namibia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NR'         => [
            'name'   => 'English (Nauru)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NZ'         => [
            'name'   => 'English (New Zealand)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NG'         => [
            'name'   => 'English (Nigeria)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NU'         => [
            'name'   => 'English (Niue)',
            'native' => '',
            'rtl'    => false
        ],
        'en_NF'         => [
            'name'   => 'English (Norfolk Island)',
            'native' => '',
            'rtl'    => false
        ],
        'en_MP'         => [
            'name'   => 'English (Northern Mariana Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PK'         => [
            'name'   => 'English (Pakistan)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PW'         => [
            'name'   => 'English (Palau)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PG'         => [
            'name'   => 'English (Papua New Guinea)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PH'         => [
            'name'   => 'English (Philippines)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PN'         => [
            'name'   => 'English (Pitcairn Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_PR'         => [
            'name'   => 'English (Puerto Rico)',
            'native' => '',
            'rtl'    => false
        ],
        'en_RW'         => [
            'name'   => 'English (Rwanda)',
            'native' => '',
            'rtl'    => false
        ],
        'en_WS'         => [
            'name'   => 'English (Samoa)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SC'         => [
            'name'   => 'English (Seychelles)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SL'         => [
            'name'   => 'English (Sierra Leone)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SG'         => [
            'name'   => 'English (Singapore)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SX'         => [
            'name'   => 'English (Sint Maarten)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SB'         => [
            'name'   => 'English (Solomon Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_ZA'         => [
            'name'   => 'English (South Africa)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SS'         => [
            'name'   => 'English (South Sudan)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SH'         => [
            'name'   => 'English (St. Helena)',
            'native' => '',
            'rtl'    => false
        ],
        'en_KN'         => [
            'name'   => 'English (St. Kitts & Nevis)',
            'native' => '',
            'rtl'    => false
        ],
        'en_LC'         => [
            'name'   => 'English (St. Lucia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_VC'         => [
            'name'   => 'English (St. Vincent & Grenadines)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SD'         => [
            'name'   => 'English (Sudan)',
            'native' => '',
            'rtl'    => false
        ],
        'en_SZ'         => [
            'name'   => 'English (Swaziland)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TZ'         => [
            'name'   => 'English (Tanzania)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TK'         => [
            'name'   => 'English (Tokelau)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TO'         => [
            'name'   => 'English (Tonga)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TT'         => [
            'name'   => 'English (Trinidad & Tobago)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TC'         => [
            'name'   => 'English (Turks & Caicos Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_TV'         => [
            'name'   => 'English (Tuvalu)',
            'native' => '',
            'rtl'    => false
        ],
        'en_UM'         => [
            'name'   => 'English (U.S. Outlying Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_VI'         => [
            'name'   => 'English (U.S. Virgin Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'en_UG'         => [
            'name'   => 'English (Uganda)',
            'native' => '',
            'rtl'    => false
        ],
        'en_GB'         => [
            'name'   => 'English (United Kingdom)',
            'native' => '',
            'rtl'    => false
        ],
        'en_US'         => [
            'name'   => 'English (United States)',
            'native' => '',
            'rtl'    => false
        ],
        'en_VU'         => [
            'name'   => 'English (Vanuatu)',
            'native' => '',
            'rtl'    => false
        ],
        'en_ZM'         => [
            'name'   => 'English (Zambia)',
            'native' => '',
            'rtl'    => false
        ],
        'en_ZW'         => [
            'name'   => 'English (Zimbabwe)',
            'native' => '',
            'rtl'    => false
        ],
        'eo'            => [
            'name'   => 'Esperanto',
            'native' => '',
            'rtl'    => false
        ],
        'et'            => [
            'name'   => 'Estonian',
            'native' => '',
            'rtl'    => false
        ],
        'et_EE'         => [
            'name'   => 'Estonian (Estonia)',
            'native' => '',
            'rtl'    => false
        ],
        'ee'            => [
            'name'   => 'Ewe',
            'native' => '',
            'rtl'    => false
        ],
        'ee_GH'         => [
            'name'   => 'Ewe (Ghana)',
            'native' => '',
            'rtl'    => false
        ],
        'ee_TG'         => [
            'name'   => 'Ewe (Togo)',
            'native' => '',
            'rtl'    => false
        ],
        'fo'            => [
            'name'   => 'Faroese',
            'native' => '',
            'rtl'    => false
        ],
        'fo_FO'         => [
            'name'   => 'Faroese (Faroe Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'fi'            => [
            'name'   => 'Finnish',
            'native' => '',
            'rtl'    => false
        ],
        'fi_FI'         => [
            'name'   => 'Finnish (Finland)',
            'native' => '',
            'rtl'    => false
        ],
        'fr'            => [
            'name'   => 'French',
            'native' => '',
            'rtl'    => false
        ],
        'fr_DZ'         => [
            'name'   => 'French (Algeria)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_BE'         => [
            'name'   => 'French (Belgium)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_BJ'         => [
            'name'   => 'French (Benin)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_BF'         => [
            'name'   => 'French (Burkina Faso)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_BI'         => [
            'name'   => 'French (Burundi)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CM'         => [
            'name'   => 'French (Cameroon)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CA'         => [
            'name'   => 'French (Canada)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CF'         => [
            'name'   => 'French (Central African Republic)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_TD'         => [
            'name'   => 'French (Chad)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_KM'         => [
            'name'   => 'French (Comoros)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CG'         => [
            'name'   => 'French (Congo - Brazzaville)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CD'         => [
            'name'   => 'French (Congo - Kinshasa)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CI'         => [
            'name'   => 'French (Côte d’Ivoire)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_DJ'         => [
            'name'   => 'French (Djibouti)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_GQ'         => [
            'name'   => 'French (Equatorial Guinea)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_FR'         => [
            'name'   => 'French (France)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_GF'         => [
            'name'   => 'French (French Guiana)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_PF'         => [
            'name'   => 'French (French Polynesia)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_GA'         => [
            'name'   => 'French (Gabon)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_GP'         => [
            'name'   => 'French (Guadeloupe)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_GN'         => [
            'name'   => 'French (Guinea)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_HT'         => [
            'name'   => 'French (Haiti)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_LU'         => [
            'name'   => 'French (Luxembourg)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MG'         => [
            'name'   => 'French (Madagascar)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_ML'         => [
            'name'   => 'French (Mali)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MQ'         => [
            'name'   => 'French (Martinique)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MR'         => [
            'name'   => 'French (Mauritania)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MU'         => [
            'name'   => 'French (Mauritius)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_YT'         => [
            'name'   => 'French (Mayotte)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MC'         => [
            'name'   => 'French (Monaco)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MA'         => [
            'name'   => 'French (Morocco)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_NC'         => [
            'name'   => 'French (New Caledonia)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_NE'         => [
            'name'   => 'French (Niger)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_RE'         => [
            'name'   => 'French (Réunion)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_RW'         => [
            'name'   => 'French (Rwanda)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_SN'         => [
            'name'   => 'French (Senegal)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_SC'         => [
            'name'   => 'French (Seychelles)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_BL'         => [
            'name'   => 'French (St. Barthélemy)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_MF'         => [
            'name'   => 'French (St. Martin)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_PM'         => [
            'name'   => 'French (St. Pierre & Miquelon)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_CH'         => [
            'name'   => 'French (Switzerland)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_SY'         => [
            'name'   => 'French (Syria)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_TG'         => [
            'name'   => 'French (Togo)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_TN'         => [
            'name'   => 'French (Tunisia)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_VU'         => [
            'name'   => 'French (Vanuatu)',
            'native' => '',
            'rtl'    => false
        ],
        'fr_WF'         => [
            'name'   => 'French (Wallis & Futuna)',
            'native' => '',
            'rtl'    => false
        ],
        'ff'            => [
            'name'   => 'Fulah',
            'native' => '',
            'rtl'    => false
        ],
        'ff_CM'         => [
            'name'   => 'Fulah (Cameroon)',
            'native' => '',
            'rtl'    => false
        ],
        'ff_GN'         => [
            'name'   => 'Fulah (Guinea)',
            'native' => '',
            'rtl'    => false
        ],
        'ff_MR'         => [
            'name'   => 'Fulah (Mauritania)',
            'native' => '',
            'rtl'    => false
        ],
        'ff_SN'         => [
            'name'   => 'Fulah (Senegal)',
            'native' => '',
            'rtl'    => false
        ],
        'gl'            => [
            'name'   => 'Galician',
            'native' => '',
            'rtl'    => false
        ],
        'gl_ES'         => [
            'name'   => 'Galician (Spain)',
            'native' => '',
            'rtl'    => false
        ],
        'lg'            => [
            'name'   => 'Ganda',
            'native' => '',
            'rtl'    => false
        ],
        'lg_UG'         => [
            'name'   => 'Ganda (Uganda)',
            'native' => '',
            'rtl'    => false
        ],
        'ka'            => [
            'name'   => 'Georgian',
            'native' => '',
            'rtl'    => false
        ],
        'ka_GE'         => [
            'name'   => 'Georgian (Georgia)',
            'native' => '',
            'rtl'    => false
        ],
        'de'            => [
            'name'   => 'German',
            'native' => '',
            'rtl'    => false
        ],
        'de_AT'         => [
            'name'   => 'German (Austria)',
            'native' => '',
            'rtl'    => false
        ],
        'de_BE'         => [
            'name'   => 'German (Belgium)',
            'native' => '',
            'rtl'    => false
        ],
        'de_DE'         => [
            'name'   => 'German (Germany)',
            'native' => '',
            'rtl'    => false
        ],
        'de_LI'         => [
            'name'   => 'German (Liechtenstein)',
            'native' => '',
            'rtl'    => false
        ],
        'de_LU'         => [
            'name'   => 'German (Luxembourg)',
            'native' => '',
            'rtl'    => false
        ],
        'de_CH'         => [
            'name'   => 'German (Switzerland)',
            'native' => '',
            'rtl'    => false
        ],
        'el'            => [
            'name'   => 'Greek',
            'native' => 'Ελληνικά',
            'rtl'    => false
        ],
        'el_CY'         => [
            'name'   => 'Greek (Cyprus)',
            'native' => 'Ελληνικά',
            'rtl'    => false
        ],
        'el_GR'         => [
            'name'   => 'Greek (Greece)',
            'native' => 'Ελληνικά',
            'rtl'    => false
        ],
        'gu'            => [
            'name'   => 'Gujarati',
            'native' => 'ગુજરાતી',
            'rtl'    => false
        ],
        'gu_IN'         => [
            'name'   => 'Gujarati (India)',
            'native' => 'ગુજરાતી',
            'rtl'    => false
        ],
        'ha'            => [
            'name'   => 'Hausa',
            'native' => 'Hausa',
            'rtl'    => false
        ],
        'ha_GH'         => [
            'name'   => 'Hausa (Ghana)',
            'native' => 'Hausa',
            'rtl'    => false
        ],
        'ha_Latn'       => [
            'name'   => 'Hausa (Latin)',
            'native' => 'Hausa',
            'rtl'    => false
        ],
        'ha_NE'         => [
            'name'   => 'Hausa (Niger)',
            'native' => 'Hausa',
            'rtl'    => false
        ],
        'ha_NG'         => [
            'name'   => 'Hausa (Nigeria)',
            'native' => 'Hausa',
            'rtl'    => false
        ],
        'he'            => [
            'name'   => 'Hebrew',
            'native' => 'עברית',
            'rtl'    => false
        ],
        'he_IL'         => [
            'name'   => 'Hebrew (Israel)',
            'native' => 'עברית',
            'rtl'    => false
        ],
        'hi'            => [
            'name'   => 'Hindi',
            'native' => 'हिन्दी',
            'rtl'    => false
        ],
        'hi_IN'         => [
            'name'   => 'Hindi (India)',
            'native' => 'हिन्दी',
            'rtl'    => false
        ],
        'hu'            => [
            'name'   => 'Hungarian',
            'native' => 'magyar',
            'rtl'    => false
        ],
        'hu_HU'         => [
            'name'   => 'Hungarian (Hungary)',
            'native' => 'magyar',
            'rtl'    => false
        ],
        'is'            => [
            'name'   => 'Icelandic',
            'native' => 'íslenska',
            'rtl'    => false
        ],
        'is_IS'         => [
            'name'   => 'Icelandic (Iceland)',
            'native' => 'íslenska',
            'rtl'    => false
        ],
        'ig'            => [
            'name'   => 'Igbo',
            'native' => 'Igbo',
            'rtl'    => false
        ],
        'ig_NG'         => [
            'name'   => 'Igbo (Nigeria)',
            'native' => 'Igbo',
            'rtl'    => false
        ],
        'id'            => [
            'name'   => 'Indonesian',
            'native' => 'Bahasa Indonesia',
            'rtl'    => false
        ],
        'id_ID'         => [
            'name'   => 'Indonesian (Indonesia)',
            'native' => 'Bahasa Indonesia',
            'rtl'    => false
        ],
        'ga'            => [
            'name'   => 'Irish',
            'native' => 'Gaeilge',
            'rtl'    => false
        ],
        'ga_IE'         => [
            'name'   => 'Irish (Ireland)',
            'native' => 'Gaeilge',
            'rtl'    => false
        ],
        'it'            => [
            'name'   => 'Italian',
            'native' => 'italiano',
            'rtl'    => false
        ],
        'it_IT'         => [
            'name'   => 'Italian (Italy)',
            'native' => 'italiano',
            'rtl'    => false
        ],
        'it_SM'         => [
            'name'   => 'Italian (San Marino)',
            'native' => 'italiano',
            'rtl'    => false
        ],
        'it_CH'         => [
            'name'   => 'Italian (Switzerland)',
            'native' => 'italiano',
            'rtl'    => false
        ],
        'ja'            => [
            'name'   => 'Japanese',
            'native' => '日本語',
            'rtl'    => false
        ],
        'ja_JP'         => [
            'name'   => 'Japanese (Japan)',
            'native' => '日本語',
            'rtl'    => false
        ],
        'kl'            => [
            'name'   => 'Kalaallisut',
            'native' => 'kalaallisut',
            'rtl'    => false
        ],
        'kl_GL'         => [
            'name'   => 'Kalaallisut (Greenland)',
            'native' => 'kalaallisut',
            'rtl'    => false
        ],
        'kn'            => [
            'name'   => 'Kannada',
            'native' => 'ಕನ್ನಡ',
            'rtl'    => false
        ],
        'kn_IN'         => [
            'name'   => 'Kannada (India)',
            'native' => 'ಕನ್ನಡ',
            'rtl'    => false
        ],
        'ks'            => [
            'name'   => 'Kashmiri',
            'native' => 'کٲشُر',
            'rtl'    => false
        ],
        'ks_Arab'       => [
            'name'   => 'Kashmiri (Arabic)',
            'native' => 'کٲشُر',
            'rtl'    => false
        ],
        'ks_IN'         => [
            'name'   => 'Kashmiri (India)',
            'native' => 'کٲشُر',
            'rtl'    => false
        ],
        'kk'            => [
            'name'   => 'Kazakh',
            'native' => 'қазақ тілі',
            'rtl'    => false
        ],
        'kk_Cyrl'       => [
            'name'   => 'Kazakh (Cyrillic)',
            'native' => 'қазақ тілі',
            'rtl'    => false
        ],
        'kk_KZ'         => [
            'name'   => 'Kazakh (Kazakhstan)',
            'native' => 'қазақ тілі',
            'rtl'    => false
        ],
        'km'            => [
            'name'   => 'Khmer',
            'native' => 'ខ្មែរ',
            'rtl'    => false
        ],
        'km_KH'         => [
            'name'   => 'Khmer (Cambodia)',
            'native' => 'ខ្មែរ',
            'rtl'    => false
        ],
        'ki'            => [
            'name'   => 'Kikuyu',
            'native' => 'Gikuyu',
            'rtl'    => false
        ],
        'ki_KE'         => [
            'name'   => 'Kikuyu (Kenya)',
            'native' => 'Gikuyu',
            'rtl'    => false
        ],
        'rw'            => [
            'name'   => 'Kinyarwanda',
            'native' => 'Kinyarwanda',
            'rtl'    => false
        ],
        'rw_RW'         => [
            'name'   => 'Kinyarwanda (Rwanda)',
            'native' => 'Kinyarwanda',
            'rtl'    => false
        ],
        'ko'            => [
            'name'   => 'Korean',
            'native' => '한국어',
            'rtl'    => false
        ],
        'ko_KP'         => [
            'name'   => 'Korean (North Korea)',
            'native' => '한국어',
            'rtl'    => false
        ],
        'ko_KR'         => [
            'name'   => 'Korean (South Korea)',
            'native' => '한국어',
            'rtl'    => false
        ],
        'ky'            => [
            'name'   => 'Kyrgyz',
            'native' => 'кыргызча',
            'rtl'    => false
        ],
        'ky_Cyrl'       => [
            'name'   => 'Kyrgyz (Cyrillic)',
            'native' => 'кыргызча',
            'rtl'    => false
        ],
        'ky_KG'         => [
            'name'   => 'Kyrgyz (Kyrgyzstan)',
            'native' => 'кыргызча',
            'rtl'    => false
        ],
        'lo'            => [
            'name'   => 'Lao',
            'native' => 'ລາວ',
            'rtl'    => false
        ],
        'lo_LA'         => [
            'name'   => 'Lao (Laos)',
            'native' => 'ລາວ',
            'rtl'    => false
        ],
        'lv'            => [
            'name'   => 'Latvian',
            'native' => 'latviešu',
            'rtl'    => false
        ],
        'lv_LV'         => [
            'name'   => 'Latvian (Latvia)',
            'native' => 'latviešu',
            'rtl'    => false
        ],
        'ln'            => [
            'name'   => 'Lingala',
            'native' => 'lingála',
            'rtl'    => false
        ],
        'ln_AO'         => [
            'name'   => 'Lingala (Angola)',
            'native' => 'lingála',
            'rtl'    => false
        ],
        'ln_CF'         => [
            'name'   => 'Lingala (Central African Republic)',
            'native' => 'lingála',
            'rtl'    => false
        ],
        'ln_CG'         => [
            'name'   => 'Lingala (Congo - Brazzaville)',
            'native' => 'lingála',
            'rtl'    => false
        ],
        'ln_CD'         => [
            'name'   => 'Lingala (Congo - Kinshasa)',
            'native' => 'lingála',
            'rtl'    => false
        ],
        'lt'            => [
            'name'   => 'Lithuanian',
            'native' => 'lietuvių',
            'rtl'    => false
        ],
        'lt_LT'         => [
            'name'   => 'Lithuanian (Lithuania)',
            'native' => 'lietuvių',
            'rtl'    => false
        ],
        'lu'            => [
            'name'   => 'Luba-Katanga',
            'native' => 'Tshiluba',
            'rtl'    => false
        ],
        'lu_CD'         => [
            'name'   => 'Luba-Katanga (Congo - Kinshasa)',
            'native' => 'Tshiluba',
            'rtl'    => false
        ],
        'lb'            => [
            'name'   => 'Luxembourgish',
            'native' => 'Lëtzebuergesch',
            'rtl'    => false
        ],
        'lb_LU'         => [
            'name'   => 'Luxembourgish (Luxembourg)',
            'native' => 'Lëtzebuergesch',
            'rtl'    => false
        ],
        'mk'            => [
            'name'   => 'Macedonian',
            'native' => 'македонски',
            'rtl'    => false
        ],
        'mk_MK'         => [
            'name'   => 'Macedonian (Macedonia)',
            'native' => 'македонски',
            'rtl'    => false
        ],
        'mg'            => [
            'name'   => 'Malagasy',
            'native' => 'Malagasy',
            'rtl'    => false
        ],
        'mg_MG'         => [
            'name'   => 'Malagasy (Madagascar)',
            'native' => 'Malagasy',
            'rtl'    => false
        ],
        'ms'            => [
            'name'   => 'Malay',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_BN'         => [
            'name'   => 'Malay (Brunei)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_Latn_BN'    => [
            'name'   => 'Malay (Latin, Brunei)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_Latn_MY'    => [
            'name'   => 'Malay (Latin, Malaysia)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_Latn_SG'    => [
            'name'   => 'Malay (Latin, Singapore)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_Latn'       => [
            'name'   => 'Malay (Latin)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_MY'         => [
            'name'   => 'Malay (Malaysia)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ms_SG'         => [
            'name'   => 'Malay (Singapore)',
            'native' => 'Bahasa Melayu',
            'rtl'    => false
        ],
        'ml'            => [
            'name'   => 'Malayalam',
            'native' => 'മലയാളം',
            'rtl'    => false
        ],
        'ml_IN'         => [
            'name'   => 'Malayalam (India)',
            'native' => 'മലയാളം',
            'rtl'    => false
        ],
        'mt'            => [
            'name'   => 'Maltese',
            'native' => 'Malti',
            'rtl'    => false
        ],
        'mt_MT'         => [
            'name'   => 'Maltese (Malta)',
            'native' => 'Malti',
            'rtl'    => false
        ],
        'gv'            => [
            'name'   => 'Manx',
            'native' => 'Gaelg',
            'rtl'    => false
        ],
        'gv_IM'         => [
            'name'   => 'Manx (Isle of Man)',
            'native' => 'Gaelg',
            'rtl'    => false
        ],
        'mr'            => [
            'name'   => 'Marathi',
            'native' => 'मराठी',
            'rtl'    => false
        ],
        'mr_IN'         => [
            'name'   => 'Marathi (India)',
            'native' => 'मराठी',
            'rtl'    => false
        ],
        'mn'            => [
            'name'   => 'Mongolian',
            'native' => 'монгол',
            'rtl'    => false
        ],
        'mn_Cyrl_MN'    => [
            'name'   => 'Mongolian (Cyrillic, Mongolia)',
            'native' => 'монгол',
            'rtl'    => false
        ],
        'mn_Cyrl'       => [
            'name'   => 'Mongolian (Cyrillic)',
            'native' => 'монгол',
            'rtl'    => false
        ],
        'mn_MN'         => [
            'name'   => 'Mongolian (Mongolia)',
            'native' => 'монгол',
            'rtl'    => false
        ],
        'ne'            => [
            'name'   => 'Nepali',
            'native' => 'नेपाली',
            'rtl'    => false
        ],
        'ne_IN'         => [
            'name'   => 'Nepali (India)',
            'native' => 'नेपाली',
            'rtl'    => false
        ],
        'ne_NP'         => [
            'name'   => 'Nepali (Nepal)',
            'native' => 'नेपाली',
            'rtl'    => false
        ],
        'nd'            => [
            'name'   => 'North Ndebele',
            'native' => 'isiNdebele',
            'rtl'    => false
        ],
        'nd_ZW'         => [
            'name'   => 'North Ndebele (Zimbabwe)',
            'native' => 'isiNdebele',
            'rtl'    => false
        ],
        'se'            => [
            'name'   => 'Northern Sami',
            'native' => 'davvisámegiella',
            'rtl'    => false
        ],
        'se_FI'         => [
            'name'   => 'Northern Sami (Finland)',
            'native' => 'davvisámegiella',
            'rtl'    => false
        ],
        'se_NO'         => [
            'name'   => 'Northern Sami (Norway)',
            'native' => 'davvisámegiella',
            'rtl'    => false
        ],
        'se_SE'         => [
            'name'   => 'Northern Sami (Sweden)',
            'native' => 'davvisámegiella',
            'rtl'    => false
        ],
        'no'            => [
            'name'   => 'Norwegian',
            'native' => 'norsk',
            'rtl'    => false
        ],
        'no_NO'         => [
            'name'   => 'Norwegian (Norway)',
            'native' => 'norsk',
            'rtl'    => false
        ],
        'nb'            => [
            'name'   => 'Norwegian Bokmål',
            'native' => 'norsk bokmål',
            'rtl'    => false
        ],
        'nb_NO'         => [
            'name'   => 'Norwegian Bokmål (Norway)',
            'native' => 'norsk bokmål',
            'rtl'    => false
        ],
        'nb_SJ'         => [
            'name'   => 'Norwegian Bokmål (Svalbard & Jan Mayen)',
            'native' => 'norsk bokmål',
            'rtl'    => false
        ],
        'nn'            => [
            'name'   => 'Norwegian Nynorsk',
            'native' => 'nynorsk',
            'rtl'    => false
        ],
        'nn_NO'         => [
            'name'   => 'Norwegian Nynorsk (Norway)',
            'native' => 'nynorsk',
            'rtl'    => false
        ],
        'or'            => [
            'name'   => 'Oriya',
            'native' => 'ଓଡ଼ିଆ',
            'rtl'    => false
        ],
        'or_IN'         => [
            'name'   => 'Oriya (India)',
            'native' => 'ଓଡ଼ିଆ',
            'rtl'    => false
        ],
        'om'            => [
            'name'   => 'Oromo',
            'native' => 'Oromoo',
            'rtl'    => false
        ],
        'om_ET'         => [
            'name'   => 'Oromo (Ethiopia)',
            'native' => 'Oromoo',
            'rtl'    => false
        ],
        'om_KE'         => [
            'name'   => 'Oromo (Kenya)',
            'native' => 'Oromoo',
            'rtl'    => false
        ],
        'os'            => [
            'name'   => 'Ossetic',
            'native' => 'ирон',
            'rtl'    => false
        ],
        'os_GE'         => [
            'name'   => 'Ossetic (Georgia)',
            'native' => 'ирон',
            'rtl'    => false
        ],
        'os_RU'         => [
            'name'   => 'Ossetic (Russia)',
            'native' => 'ирон',
            'rtl'    => false
        ],
        'ps'            => [
            'name'   => 'Pashto',
            'native' => 'پښتو',
            'rtl'    => false
        ],
        'ps_AF'         => [
            'name'   => 'Pashto (Afghanistan)',
            'native' => 'پښتو',
            'rtl'    => false
        ],
        'fa'            => [
            'name'   => 'Persian',
            'native' => 'فارسی',
            'rtl'    => false
        ],
        'fa_AF'         => [
            'name'   => 'Persian (Afghanistan)',
            'native' => 'فارسی',
            'rtl'    => false
        ],
        'fa_IR'         => [
            'name'   => 'Persian (Iran)',
            'native' => 'فارسی',
            'rtl'    => false
        ],
        'pl'            => [
            'name'   => 'Polish',
            'native' => 'polski',
            'rtl'    => false
        ],
        'pl_PL'         => [
            'name'   => 'Polish (Poland)',
            'native' => 'polski',
            'rtl'    => false
        ],
        'pt'            => [
            'name'   => 'Portuguese',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_AO'         => [
            'name'   => 'Portuguese (Angola)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_BR'         => [
            'name'   => 'Portuguese (Brazil)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_CV'         => [
            'name'   => 'Portuguese (Cape Verde)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_GW'         => [
            'name'   => 'Portuguese (Guinea-Bissau)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_MO'         => [
            'name'   => 'Portuguese (Macau SAR China)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_MZ'         => [
            'name'   => 'Portuguese (Mozambique)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_PT'         => [
            'name'   => 'Portuguese (Portugal)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_ST'         => [
            'name'   => 'Portuguese (São Tomé & Príncipe)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pt_TL'         => [
            'name'   => 'Portuguese (Timor-Leste)',
            'native' => 'português',
            'rtl'    => false
        ],
        'pa'            => [
            'name'   => 'Punjabi',
            'native' => 'ਪੰਜਾਬੀ',
            'rtl'    => false
        ],
        'pa_Arab'       => [
            'name'   => 'Punjabi (Arabic)',
            'native' => 'پنجابی',
            'rtl'    => false
        ],
        'pa_Guru'       => [
            'name'   => 'Punjabi (Gurmukhi)',
            'native' => 'ਪੰਜਾਬੀ',
            'rtl'    => false
        ],
        'pa_IN'         => [
            'name'   => 'Punjabi (India)',
            'native' => 'ਪੰਜਾਬੀ',
            'rtl'    => false
        ],
        'pa_PK'         => [
            'name'   => 'Punjabi (Pakistan)',
            'native' => 'ਪੰਜਾਬੀ',
            'rtl'    => false
        ],
        'qu'            => [
            'name'   => 'Quechua',
            'native' => 'Runasimi',
            'rtl'    => false
        ],
        'qu_BO'         => [
            'name'   => 'Quechua (Bolivia)',
            'native' => 'Runasimi',
            'rtl'    => false
        ],
        'qu_EC'         => [
            'name'   => 'Quechua (Ecuador)',
            'native' => 'Runasimi',
            'rtl'    => false
        ],
        'qu_PE'         => [
            'name'   => 'Quechua (Peru)',
            'native' => 'Runasimi',
            'rtl'    => false
        ],
        'ro'            => [
            'name'   => 'Romanian',
            'native' => 'română',
            'rtl'    => false
        ],
        'ro_MD'         => [
            'name'   => 'Romanian (Moldova)',
            'native' => 'română',
            'rtl'    => false
        ],
        'ro_RO'         => [
            'name'   => 'Romanian (Romania)',
            'native' => 'română',
            'rtl'    => false
        ],
        'rm'            => [
            'name'   => 'Romansh',
            'native' => 'rumantsch',
            'rtl'    => false
        ],
        'rm_CH'         => [
            'name'   => 'Romansh (Switzerland)',
            'native' => 'rumantsch',
            'rtl'    => false
        ],
        'rn'            => [
            'name'   => 'Rundi',
            'native' => 'Ikirundi',
            'rtl'    => false
        ],
        'rn_BI'         => [
            'name'   => 'Rundi (Burundi)',
            'native' => 'Ikirundi',
            'rtl'    => false
        ],
        'ru'            => [
            'name'   => 'Russian',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_BY'         => [
            'name'   => 'Russian (Belarus)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_KZ'         => [
            'name'   => 'Russian (Kazakhstan)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_KG'         => [
            'name'   => 'Russian (Kyrgyzstan)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_MD'         => [
            'name'   => 'Russian (Moldova)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_RU'         => [
            'name'   => 'Russian (Russia)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'ru_UA'         => [
            'name'   => 'Russian (Ukraine)',
            'native' => 'русский',
            'rtl'    => false
        ],
        'sg'            => [
            'name'   => 'Sango',
            'native' => 'Sängö',
            'rtl'    => false
        ],
        'sg_CF'         => [
            'name'   => 'Sango (Central African Republic)',
            'native' => 'Sängö',
            'rtl'    => false
        ],
        'gd'            => [
            'name'   => 'Scottish Gaelic',
            'native' => 'Gàidhlig',
            'rtl'    => false
        ],
        'gd_GB'         => [
            'name'   => 'Scottish Gaelic (United Kingdom)',
            'native' => 'Gàidhlig',
            'rtl'    => false
        ],
        'sr'            => [
            'name'   => 'Serbian',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sr_BA'         => [
            'name'   => 'Serbian (Bosnia & Herzegovina)',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sr_Cyrl'       => [
            'name'   => 'Serbian (Cyrillic)',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sr_XK'         => [
            'name'   => 'Serbian (Kosovo)',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sr_Latn'       => [
            'name'   => 'Serbian (Latin)',
            'native' => 'srpski',
            'rtl'    => false
        ],
        'sr_ME'         => [
            'name'   => 'Serbian (Montenegro)',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sr_RS'         => [
            'name'   => 'Serbian (Serbia)',
            'native' => 'српски',
            'rtl'    => false
        ],
        'sh'            => [
            'name'   => 'Serbo-Croatian',
            'native' => 'Srpskohrvatski',
            'rtl'    => false
        ],
        'sh_BA'         => [
            'name'   => 'Serbo-Croatian (Bosnia & Herzegovina)',
            'native' => 'Srpskohrvatski',
            'rtl'    => false
        ],
        'sn'            => [
            'name'   => 'Shona',
            'native' => 'chiShona',
            'rtl'    => false
        ],
        'sn_ZW'         => [
            'name'   => 'Shona (Zimbabwe)',
            'native' => 'chiShona',
            'rtl'    => false
        ],
        'ii'            => [
            'name'   => 'Sichuan Yi',
            'native' => 'ꆈꌠꉙ',
            'rtl'    => false
        ],
        'ii_CN'         => [
            'name'   => 'Sichuan Yi (China)',
            'native' => 'ꆈꌠꉙ',
            'rtl'    => false
        ],
        'si'            => [
            'name'   => 'Sinhala',
            'native' => 'සිංහල',
            'rtl'    => false
        ],
        'si_LK'         => [
            'name'   => 'Sinhala (Sri Lanka)',
            'native' => 'සිංහල',
            'rtl'    => false
        ],
        'sk'            => [
            'name'   => 'Slovak',
            'native' => 'slovenčina',
            'rtl'    => false
        ],
        'sk_SK'         => [
            'name'   => 'Slovak (Slovakia)',
            'native' => 'slovenčina',
            'rtl'    => false
        ],
        'sl'            => [
            'name'   => 'Slovenian',
            'native' => 'slovenščina',
            'rtl'    => false
        ],
        'sl_SI'         => [
            'name'   => 'Slovenian (Slovenia)',
            'native' => 'slovenščina',
            'rtl'    => false
        ],
        'so'            => [
            'name'   => 'Somali',
            'native' => 'Soomaali',
            'rtl'    => false
        ],
        'so_DJ'         => [
            'name'   => 'Somali (Djibouti)',
            'native' => 'Soomaali',
            'rtl'    => false
        ],
        'so_ET'         => [
            'name'   => 'Somali (Ethiopia)',
            'native' => 'Soomaali',
            'rtl'    => false
        ],
        'so_KE'         => [
            'name'   => 'Somali (Kenya)',
            'native' => 'Soomaali',
            'rtl'    => false
        ],
        'so_SO'         => [
            'name'   => 'Somali (Somalia)',
            'native' => 'Soomaali',
            'rtl'    => false
        ],
        'es'            => [
            'name'   => 'Spanish',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_AR'         => [
            'name'   => 'Spanish (Argentina)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_BO'         => [
            'name'   => 'Spanish (Bolivia)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_IC'         => [
            'name'   => 'Spanish (Canary Islands)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_EA'         => [
            'name'   => 'Spanish (Ceuta & Melilla)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_CL'         => [
            'name'   => 'Spanish (Chile)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_CO'         => [
            'name'   => 'Spanish (Colombia)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_CR'         => [
            'name'   => 'Spanish (Costa Rica)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_CU'         => [
            'name'   => 'Spanish (Cuba)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_DO'         => [
            'name'   => 'Spanish (Dominican Republic)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_EC'         => [
            'name'   => 'Spanish (Ecuador)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_SV'         => [
            'name'   => 'Spanish (El Salvador)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_GQ'         => [
            'name'   => 'Spanish (Equatorial Guinea)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_GT'         => [
            'name'   => 'Spanish (Guatemala)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_HN'         => [
            'name'   => 'Spanish (Honduras)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_MX'         => [
            'name'   => 'Spanish (Mexico)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_NI'         => [
            'name'   => 'Spanish (Nicaragua)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_PA'         => [
            'name'   => 'Spanish (Panama)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_PY'         => [
            'name'   => 'Spanish (Paraguay)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_PE'         => [
            'name'   => 'Spanish (Peru)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_PH'         => [
            'name'   => 'Spanish (Philippines)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_PR'         => [
            'name'   => 'Spanish (Puerto Rico)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_ES'         => [
            'name'   => 'Spanish (Spain)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_US'         => [
            'name'   => 'Spanish (United States)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_UY'         => [
            'name'   => 'Spanish (Uruguay)',
            'native' => 'español',
            'rtl'    => false
        ],
        'es_VE'         => [
            'name'   => 'Spanish (Venezuela)',
            'native' => 'español',
            'rtl'    => false
        ],
        'sw'            => [
            'name'   => 'Swahili',
            'native' => 'Kiswahili',
            'rtl'    => false
        ],
        'sw_KE'         => [
            'name'   => 'Swahili (Kenya)',
            'native' => 'Kiswahili',
            'rtl'    => false
        ],
        'sw_TZ'         => [
            'name'   => 'Swahili (Tanzania)',
            'native' => 'Kiswahili',
            'rtl'    => false
        ],
        'sw_UG'         => [
            'name'   => 'Swahili (Uganda)',
            'native' => 'Kiswahili',
            'rtl'    => false
        ],
        'sv'            => [
            'name'   => 'Swedish',
            'native' => 'svenska',
            'rtl'    => false
        ],
        'sv_AX'         => [
            'name'   => 'Swedish (Åland Islands)',
            'native' => 'svenska',
            'rtl'    => false
        ],
        'sv_FI'         => [
            'name'   => 'Swedish (Finland)',
            'native' => 'svenska',
            'rtl'    => false
        ],
        'sv_SE'         => [
            'name'   => 'Swedish (Sweden)',
            'native' => 'svenska',
            'rtl'    => false
        ],
        'tl'            => [
            'name'   => 'Tagalog',
            'native' => 'Tagalog',
            'rtl'    => false
        ],
        'tl_PH'         => [
            'name'   => 'Tagalog (Philippines)',
            'native' => 'Tagalog',
            'rtl'    => false
        ],
        'ta'            => [
            'name'   => 'Tamil',
            'native' => 'தமிழ்',
            'rtl'    => false
        ],
        'ta_IN'         => [
            'name'   => 'Tamil (India)',
            'native' => 'தமிழ்',
            'rtl'    => false
        ],
        'ta_MY'         => [
            'name'   => 'Tamil (Malaysia)',
            'native' => 'தமிழ்',
            'rtl'    => false
        ],
        'ta_SG'         => [
            'name'   => 'Tamil (Singapore)',
            'native' => 'தமிழ்',
            'rtl'    => false
        ],
        'ta_LK'         => [
            'name'   => 'Tamil (Sri Lanka)',
            'native' => 'தமிழ்',
            'rtl'    => false
        ],
        'te'            => [
            'name'   => 'Telugu',
            'native' => 'తెలుగు',
            'rtl'    => false
        ],
        'te_IN'         => [
            'name'   => 'Telugu (India)',
            'native' => 'టర్కిష్',
            'rtl'    => false
        ],
        'th'            => [
            'name'   => 'Thai',
            'native' => 'ไทย',
            'rtl'    => false
        ],
        'th_TH'         => [
            'name'   => 'Thai (Thailand)',
            'native' => 'ไทย',
            'rtl'    => false
        ],
        'bo'            => [
            'name'   => 'Tibetan',
            'native' => 'བོད་སྐད་',
            'rtl'    => false
        ],
        'bo_CN'         => [
            'name'   => 'Tibetan (China)',
            'native' => 'བོད་སྐད་',
            'rtl'    => false
        ],
        'bo_IN'         => [
            'name'   => 'Tibetan (India)',
            'native' => 'བོད་སྐད་',
            'rtl'    => false
        ],
        'ti'            => [
            'name'   => 'Tigrinya',
            'native' => 'ትግርኛ',
            'rtl'    => false
        ],
        'ti_ER'         => [
            'name'   => 'Tigrinya (Eritrea)',
            'native' => 'ትግርኛ',
            'rtl'    => false
        ],
        'ti_ET'         => [
            'name'   => 'Tigrinya (Ethiopia)',
            'native' => 'ትግርኛ',
            'rtl'    => false
        ],
        'to'            => [
            'name'   => 'Tongan',
            'native' => 'lea fakatonga',
            'rtl'    => false
        ],
        'to_TO'         => [
            'name'   => 'Tongan (Tonga)',
            'native' => 'lea fakatonga',
            'rtl'    => false
        ],
        'tr'            => [
            'name'   => 'Turkish',
            'native' => 'Türkçe',
            'rtl'    => false
        ],
        'tr_CY'         => [
            'name'   => 'Turkish (Cyprus)',
            'native' => 'Türkçe',
            'rtl'    => false
        ],
        'tr_TR'         => [
            'name'   => 'Turkish (Turkey)',
            'native' => 'Türkçe',
            'rtl'    => false
        ],
        'uk'            => [
            'name'   => 'Ukrainian',
            'native' => 'українська',
            'rtl'    => false
        ],
        'uk_UA'         => [
            'name'   => 'Ukrainian (Ukraine)',
            'native' => 'українська',
            'rtl'    => false
        ],
        'ur'            => [
            'name'   => 'Urdu',
            'native' => 'اردو',
            'rtl'    => false
        ],
        'ur_IN'         => [
            'name'   => 'Urdu (India)',
            'native' => 'اردو',
            'rtl'    => false
        ],
        'ur_PK'         => [
            'name'   => 'Urdu (Pakistan)',
            'native' => 'اردو',
            'rtl'    => false
        ],
        'ug'            => [
            'name'   => 'Uyghur',
            'native' => 'ئۇيغۇرچە',
            'rtl'    => false
        ],
        'ug_Arab'       => [
            'name'   => 'Uyghur (Arabic)',
            'native' => 'ئۇيغۇرچە',
            'rtl'    => false
        ],
        'ug_CN'         => [
            'name'   => 'Uyghur (China)',
            'native' => 'ئۇيغۇرچە',
            'rtl'    => false
        ],
        'uz'            => [
            'name'   => 'Uzbek',
            'native' => 'oʻzbekcha',
            'rtl'    => false
        ],
        'uz_AF'         => [
            'name'   => 'Uzbek (Afghanistan)',
            'native' => 'oʻzbekcha',
            'rtl'    => false
        ],
        'uz_Arab'       => [
            'name'   => 'Uzbek (Arabic)',
            'native' => 'اوزبیک',
            'rtl'    => false
        ],
        'uz_Cyrl'       => [
            'name'   => 'Uzbek (Cyrillic)',
            'native' => 'Ўзбек',
            'rtl'    => false
        ],
        'uz_UZ'         => [
            'name'   => 'Uzbek (Uzbekistan)',
            'native' => 'oʻzbekcha',
            'rtl'    => false
        ],
        'vi'            => [
            'name'   => 'Vietnamese',
            'native' => 'Tiếng Việt',
            'rtl'    => false
        ],
        'vi_VN'         => [
            'name'   => 'Vietnamese (Vietnam)',
            'native' => 'Tiếng Việt',
            'rtl'    => false
        ],
        'cy'            => [
            'name'   => 'Welsh',
            'native' => 'Cymraeg',
            'rtl'    => false
        ],
        'cy_GB'         => [
            'name'   => 'Welsh (United Kingdom)',
            'native' => 'Cymraeg',
            'rtl'    => false
        ],
        'fy'            => [
            'name'   => 'Western Frisian',
            'native' => 'West-Frysk',
            'rtl'    => false
        ],
        'fy_NL'         => [
            'name'   => 'Western Frisian (Netherlands)',
            'native' => 'West-Frysk',
            'rtl'    => false
        ],
        'yi'            => [
            'name'   => 'Yiddish',
            'native' => 'ייִדיש',
            'rtl'    => false
        ],
        'yo'            => [
            'name'   => 'Yoruba',
            'native' => 'Èdè Yorùbá',
            'rtl'    => false
        ],
        'yo_BJ'         => [
            'name'   => 'Yoruba (Benin)',
            'native' => 'Èdè Yorùbá',
            'rtl'    => false
        ],
        'yo_NG'         => [
            'name'   => 'Yoruba (Nigeria)',
            'native' => 'Èdè Yorùbá',
            'rtl'    => false
        ],
        'zu'            => [
            'name'   => 'Zulu',
            'native' => 'isiZulu',
            'rtl'    => false
        ],
        'zu_ZA'         => [
            'name'   => 'Zulu (South Africa)',
            'native' => 'isiZulu',
            'rtl'    => false
        ],
    ],
];
