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
            'native' => '',
            'rtl'    => false
        ],
        'el_CY'         => [
            'name'   => 'Greek (Cyprus)',
            'native' => '',
            'rtl'    => false
        ],
        'el_GR'         => [
            'name'   => 'Greek (Greece)',
            'native' => '',
            'rtl'    => false
        ],
        'gu'            => [
            'name'   => 'Gujarati',
            'native' => '',
            'rtl'    => false
        ],
        'gu_IN'         => [
            'name'   => 'Gujarati (India)',
            'native' => '',
            'rtl'    => false
        ],
        'ha'            => [
            'name'   => 'Hausa',
            'native' => '',
            'rtl'    => false
        ],
        'ha_GH'         => [
            'name'   => 'Hausa (Ghana)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_Latn_GH'    => [
            'name'   => 'Hausa (Latin, Ghana)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_Latn_NE'    => [
            'name'   => 'Hausa (Latin, Niger)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_Latn_NG'    => [
            'name'   => 'Hausa (Latin, Nigeria)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_Latn'       => [
            'name'   => 'Hausa (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_NE'         => [
            'name'   => 'Hausa (Niger)',
            'native' => '',
            'rtl'    => false
        ],
        'ha_NG'         => [
            'name'   => 'Hausa (Nigeria)',
            'native' => '',
            'rtl'    => false
        ],
        'he'            => [
            'name'   => 'Hebrew',
            'native' => '',
            'rtl'    => false
        ],
        'he_IL'         => [
            'name'   => 'Hebrew (Israel)',
            'native' => '',
            'rtl'    => false
        ],
        'hi'            => [
            'name'   => 'Hindi',
            'native' => '',
            'rtl'    => false
        ],
        'hi_IN'         => [
            'name'   => 'Hindi (India)',
            'native' => '',
            'rtl'    => false
        ],
        'hu'            => [
            'name'   => 'Hungarian',
            'native' => '',
            'rtl'    => false
        ],
        'hu_HU'         => [
            'name'   => 'Hungarian (Hungary)',
            'native' => '',
            'rtl'    => false
        ],
        'is'            => [
            'name'   => 'Icelandic',
            'native' => '',
            'rtl'    => false
        ],
        'is_IS'         => [
            'name'   => 'Icelandic (Iceland)',
            'native' => '',
            'rtl'    => false
        ],
        'ig'            => [
            'name'   => 'Igbo',
            'native' => '',
            'rtl'    => false
        ],
        'ig_NG'         => [
            'name'   => 'Igbo (Nigeria)',
            'native' => '',
            'rtl'    => false
        ],
        'id'            => [
            'name'   => 'Indonesian',
            'native' => '',
            'rtl'    => false
        ],
        'id_ID'         => [
            'name'   => 'Indonesian (Indonesia)',
            'native' => '',
            'rtl'    => false
        ],
        'ga'            => [
            'name'   => 'Irish',
            'native' => '',
            'rtl'    => false
        ],
        'ga_IE'         => [
            'name'   => 'Irish (Ireland)',
            'native' => '',
            'rtl'    => false
        ],
        'it'            => [
            'name'   => 'Italian',
            'native' => '',
            'rtl'    => false
        ],
        'it_IT'         => [
            'name'   => 'Italian (Italy)',
            'native' => '',
            'rtl'    => false
        ],
        'it_SM'         => [
            'name'   => 'Italian (San Marino)',
            'native' => '',
            'rtl'    => false
        ],
        'it_CH'         => [
            'name'   => 'Italian (Switzerland)',
            'native' => '',
            'rtl'    => false
        ],
        'ja'            => [
            'name'   => 'Japanese',
            'native' => '',
            'rtl'    => false
        ],
        'ja_JP'         => [
            'name'   => 'Japanese (Japan)',
            'native' => '',
            'rtl'    => false
        ],
        'kl'            => [
            'name'   => 'Kalaallisut',
            'native' => '',
            'rtl'    => false
        ],
        'kl_GL'         => [
            'name'   => 'Kalaallisut (Greenland)',
            'native' => '',
            'rtl'    => false
        ],
        'kn'            => [
            'name'   => 'Kannada',
            'native' => '',
            'rtl'    => false
        ],
        'kn_IN'         => [
            'name'   => 'Kannada (India)',
            'native' => '',
            'rtl'    => false
        ],
        'ks'            => [
            'name'   => 'Kashmiri',
            'native' => '',
            'rtl'    => false
        ],
        'ks_Arab_IN'    => [
            'name'   => 'Kashmiri (Arabic, India)',
            'native' => '',
            'rtl'    => false
        ],
        'ks_Arab'       => [
            'name'   => 'Kashmiri (Arabic)',
            'native' => '',
            'rtl'    => false
        ],
        'ks_IN'         => [
            'name'   => 'Kashmiri (India)',
            'native' => '',
            'rtl'    => false
        ],
        'kk'            => [
            'name'   => 'Kazakh',
            'native' => '',
            'rtl'    => false
        ],
        'kk_Cyrl_KZ'    => [
            'name'   => 'Kazakh (Cyrillic, Kazakhstan)',
            'native' => '',
            'rtl'    => false
        ],
        'kk_Cyrl'       => [
            'name'   => 'Kazakh (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'kk_KZ'         => [
            'name'   => 'Kazakh (Kazakhstan)',
            'native' => '',
            'rtl'    => false
        ],
        'km'            => [
            'name'   => 'Khmer',
            'native' => '',
            'rtl'    => false
        ],
        'km_KH'         => [
            'name'   => 'Khmer (Cambodia)',
            'native' => '',
            'rtl'    => false
        ],
        'ki'            => [
            'name'   => 'Kikuyu',
            'native' => '',
            'rtl'    => false
        ],
        'ki_KE'         => [
            'name'   => 'Kikuyu (Kenya)',
            'native' => '',
            'rtl'    => false
        ],
        'rw'            => [
            'name'   => 'Kinyarwanda',
            'native' => '',
            'rtl'    => false
        ],
        'rw_RW'         => [
            'name'   => 'Kinyarwanda (Rwanda)',
            'native' => '',
            'rtl'    => false
        ],
        'ko'            => [
            'name'   => 'Korean',
            'native' => '',
            'rtl'    => false
        ],
        'ko_KP'         => [
            'name'   => 'Korean (North Korea)',
            'native' => '',
            'rtl'    => false
        ],
        'ko_KR'         => [
            'name'   => 'Korean (South Korea)',
            'native' => '',
            'rtl'    => false
        ],
        'ky'            => [
            'name'   => 'Kyrgyz',
            'native' => '',
            'rtl'    => false
        ],
        'ky_Cyrl_KG'    => [
            'name'   => 'Kyrgyz (Cyrillic, Kyrgyzstan)',
            'native' => '',
            'rtl'    => false
        ],
        'ky_Cyrl'       => [
            'name'   => 'Kyrgyz (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'ky_KG'         => [
            'name'   => 'Kyrgyz (Kyrgyzstan)',
            'native' => '',
            'rtl'    => false
        ],
        'lo'            => [
            'name'   => 'Lao',
            'native' => '',
            'rtl'    => false
        ],
        'lo_LA'         => [
            'name'   => 'Lao (Laos)',
            'native' => '',
            'rtl'    => false
        ],
        'lv'            => [
            'name'   => 'Latvian',
            'native' => '',
            'rtl'    => false
        ],
        'lv_LV'         => [
            'name'   => 'Latvian (Latvia)',
            'native' => '',
            'rtl'    => false
        ],
        'ln'            => [
            'name'   => 'Lingala',
            'native' => '',
            'rtl'    => false
        ],
        'ln_AO'         => [
            'name'   => 'Lingala (Angola)',
            'native' => '',
            'rtl'    => false
        ],
        'ln_CF'         => [
            'name'   => 'Lingala (Central African Republic)',
            'native' => '',
            'rtl'    => false
        ],
        'ln_CG'         => [
            'name'   => 'Lingala (Congo - Brazzaville)',
            'native' => '',
            'rtl'    => false
        ],
        'ln_CD'         => [
            'name'   => 'Lingala (Congo - Kinshasa)',
            'native' => '',
            'rtl'    => false
        ],
        'lt'            => [
            'name'   => 'Lithuanian',
            'native' => '',
            'rtl'    => false
        ],
        'lt_LT'         => [
            'name'   => 'Lithuanian (Lithuania)',
            'native' => '',
            'rtl'    => false
        ],
        'lu'            => [
            'name'   => 'Luba-Katanga',
            'native' => '',
            'rtl'    => false
        ],
        'lu_CD'         => [
            'name'   => 'Luba-Katanga (Congo - Kinshasa)',
            'native' => '',
            'rtl'    => false
        ],
        'lb'            => [
            'name'   => 'Luxembourgish',
            'native' => '',
            'rtl'    => false
        ],
        'lb_LU'         => [
            'name'   => 'Luxembourgish (Luxembourg)',
            'native' => '',
            'rtl'    => false
        ],
        'mk'            => [
            'name'   => 'Macedonian',
            'native' => '',
            'rtl'    => false
        ],
        'mk_MK'         => [
            'name'   => 'Macedonian (Macedonia)',
            'native' => '',
            'rtl'    => false
        ],
        'mg'            => [
            'name'   => 'Malagasy',
            'native' => '',
            'rtl'    => false
        ],
        'mg_MG'         => [
            'name'   => 'Malagasy (Madagascar)',
            'native' => '',
            'rtl'    => false
        ],
        'ms'            => [
            'name'   => 'Malay',
            'native' => '',
            'rtl'    => false
        ],
        'ms_BN'         => [
            'name'   => 'Malay (Brunei)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_Latn_BN'    => [
            'name'   => 'Malay (Latin, Brunei)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_Latn_MY'    => [
            'name'   => 'Malay (Latin, Malaysia)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_Latn_SG'    => [
            'name'   => 'Malay (Latin, Singapore)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_Latn'       => [
            'name'   => 'Malay (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_MY'         => [
            'name'   => 'Malay (Malaysia)',
            'native' => '',
            'rtl'    => false
        ],
        'ms_SG'         => [
            'name'   => 'Malay (Singapore)',
            'native' => '',
            'rtl'    => false
        ],
        'ml'            => [
            'name'   => 'Malayalam',
            'native' => '',
            'rtl'    => false
        ],
        'ml_IN'         => [
            'name'   => 'Malayalam (India)',
            'native' => '',
            'rtl'    => false
        ],
        'mt'            => [
            'name'   => 'Maltese',
            'native' => '',
            'rtl'    => false
        ],
        'mt_MT'         => [
            'name'   => 'Maltese (Malta)',
            'native' => '',
            'rtl'    => false
        ],
        'gv'            => [
            'name'   => 'Manx',
            'native' => '',
            'rtl'    => false
        ],
        'gv_IM'         => [
            'name'   => 'Manx (Isle of Man)',
            'native' => '',
            'rtl'    => false
        ],
        'mr'            => [
            'name'   => 'Marathi',
            'native' => '',
            'rtl'    => false
        ],
        'mr_IN'         => [
            'name'   => 'Marathi (India)',
            'native' => '',
            'rtl'    => false
        ],
        'mn'            => [
            'name'   => 'Mongolian',
            'native' => '',
            'rtl'    => false
        ],
        'mn_Cyrl_MN'    => [
            'name'   => 'Mongolian (Cyrillic, Mongolia)',
            'native' => '',
            'rtl'    => false
        ],
        'mn_Cyrl'       => [
            'name'   => 'Mongolian (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'mn_MN'         => [
            'name'   => 'Mongolian (Mongolia)',
            'native' => '',
            'rtl'    => false
        ],
        'ne'            => [
            'name'   => 'Nepali',
            'native' => '',
            'rtl'    => false
        ],
        'ne_IN'         => [
            'name'   => 'Nepali (India)',
            'native' => '',
            'rtl'    => false
        ],
        'ne_NP'         => [
            'name'   => 'Nepali (Nepal)',
            'native' => '',
            'rtl'    => false
        ],
        'nd'            => [
            'name'   => 'North Ndebele',
            'native' => '',
            'rtl'    => false
        ],
        'nd_ZW'         => [
            'name'   => 'North Ndebele (Zimbabwe)',
            'native' => '',
            'rtl'    => false
        ],
        'se'            => [
            'name'   => 'Northern Sami',
            'native' => '',
            'rtl'    => false
        ],
        'se_FI'         => [
            'name'   => 'Northern Sami (Finland)',
            'native' => '',
            'rtl'    => false
        ],
        'se_NO'         => [
            'name'   => 'Northern Sami (Norway)',
            'native' => '',
            'rtl'    => false
        ],
        'se_SE'         => [
            'name'   => 'Northern Sami (Sweden)',
            'native' => '',
            'rtl'    => false
        ],
        'no'            => [
            'name'   => 'Norwegian',
            'native' => '',
            'rtl'    => false
        ],
        'no_NO'         => [
            'name'   => 'Norwegian (Norway)',
            'native' => '',
            'rtl'    => false
        ],
        'nb'            => [
            'name'   => 'Norwegian Bokmål',
            'native' => '',
            'rtl'    => false
        ],
        'nb_NO'         => [
            'name'   => 'Norwegian Bokmål (Norway)',
            'native' => '',
            'rtl'    => false
        ],
        'nb_SJ'         => [
            'name'   => 'Norwegian Bokmål (Svalbard & Jan Mayen)',
            'native' => '',
            'rtl'    => false
        ],
        'nn'            => [
            'name'   => 'Norwegian Nynorsk',
            'native' => '',
            'rtl'    => false
        ],
        'nn_NO'         => [
            'name'   => 'Norwegian Nynorsk (Norway)',
            'native' => '',
            'rtl'    => false
        ],
        'or'            => [
            'name'   => 'Oriya',
            'native' => '',
            'rtl'    => false
        ],
        'or_IN'         => [
            'name'   => 'Oriya (India)',
            'native' => '',
            'rtl'    => false
        ],
        'om'            => [
            'name'   => 'Oromo',
            'native' => '',
            'rtl'    => false
        ],
        'om_ET'         => [
            'name'   => 'Oromo (Ethiopia)',
            'native' => '',
            'rtl'    => false
        ],
        'om_KE'         => [
            'name'   => 'Oromo (Kenya)',
            'native' => '',
            'rtl'    => false
        ],
        'os'            => [
            'name'   => 'Ossetic',
            'native' => '',
            'rtl'    => false
        ],
        'os_GE'         => [
            'name'   => 'Ossetic (Georgia)',
            'native' => '',
            'rtl'    => false
        ],
        'os_RU'         => [
            'name'   => 'Ossetic (Russia)',
            'native' => '',
            'rtl'    => false
        ],
        'ps'            => [
            'name'   => 'Pashto',
            'native' => '',
            'rtl'    => false
        ],
        'ps_AF'         => [
            'name'   => 'Pashto (Afghanistan)',
            'native' => '',
            'rtl'    => false
        ],
        'fa'            => [
            'name'   => 'Persian',
            'native' => '',
            'rtl'    => false
        ],
        'fa_AF'         => [
            'name'   => 'Persian (Afghanistan)',
            'native' => '',
            'rtl'    => false
        ],
        'fa_IR'         => [
            'name'   => 'Persian (Iran)',
            'native' => '',
            'rtl'    => false
        ],
        'pl'            => [
            'name'   => 'Polish',
            'native' => '',
            'rtl'    => false
        ],
        'pl_PL'         => [
            'name'   => 'Polish (Poland)',
            'native' => '',
            'rtl'    => false
        ],
        'pt'            => [
            'name'   => 'Portuguese',
            'native' => '',
            'rtl'    => false
        ],
        'pt_AO'         => [
            'name'   => 'Portuguese (Angola)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_BR'         => [
            'name'   => 'Portuguese (Brazil)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_CV'         => [
            'name'   => 'Portuguese (Cape Verde)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_GW'         => [
            'name'   => 'Portuguese (Guinea-Bissau)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_MO'         => [
            'name'   => 'Portuguese (Macau SAR China)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_MZ'         => [
            'name'   => 'Portuguese (Mozambique)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_PT'         => [
            'name'   => 'Portuguese (Portugal)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_ST'         => [
            'name'   => 'Portuguese (São Tomé & Príncipe)',
            'native' => '',
            'rtl'    => false
        ],
        'pt_TL'         => [
            'name'   => 'Portuguese (Timor-Leste)',
            'native' => '',
            'rtl'    => false
        ],
        'pa'            => [
            'name'   => 'Punjabi',
            'native' => '',
            'rtl'    => false
        ],
        'pa_Arab_PK'    => [
            'name'   => 'Punjabi (Arabic, Pakistan)',
            'native' => '',
            'rtl'    => false
        ],
        'pa_Arab'       => [
            'name'   => 'Punjabi (Arabic)',
            'native' => '',
            'rtl'    => false
        ],
        'pa_Guru_IN'    => [
            'name'   => 'Punjabi (Gurmukhi, India)',
            'native' => '',
            'rtl'    => false
        ],
        'pa_Guru'       => [
            'name'   => 'Punjabi (Gurmukhi)',
            'native' => '',
            'rtl'    => false
        ],
        'pa_IN'         => [
            'name'   => 'Punjabi (India)',
            'native' => '',
            'rtl'    => false
        ],
        'pa_PK'         => [
            'name'   => 'Punjabi (Pakistan)',
            'native' => '',
            'rtl'    => false
        ],
        'qu'            => [
            'name'   => 'Quechua',
            'native' => '',
            'rtl'    => false
        ],
        'qu_BO'         => [
            'name'   => 'Quechua (Bolivia)',
            'native' => '',
            'rtl'    => false
        ],
        'qu_EC'         => [
            'name'   => 'Quechua (Ecuador)',
            'native' => '',
            'rtl'    => false
        ],
        'qu_PE'         => [
            'name'   => 'Quechua (Peru)',
            'native' => '',
            'rtl'    => false
        ],
        'ro'            => [
            'name'   => 'Romanian',
            'native' => '',
            'rtl'    => false
        ],
        'ro_MD'         => [
            'name'   => 'Romanian (Moldova)',
            'native' => '',
            'rtl'    => false
        ],
        'ro_RO'         => [
            'name'   => 'Romanian (Romania)',
            'native' => '',
            'rtl'    => false
        ],
        'rm'            => [
            'name'   => 'Romansh',
            'native' => '',
            'rtl'    => false
        ],
        'rm_CH'         => [
            'name'   => 'Romansh (Switzerland)',
            'native' => '',
            'rtl'    => false
        ],
        'rn'            => [
            'name'   => 'Rundi',
            'native' => '',
            'rtl'    => false
        ],
        'rn_BI'         => [
            'name'   => 'Rundi (Burundi)',
            'native' => '',
            'rtl'    => false
        ],
        'ru'            => [
            'name'   => 'Russian',
            'native' => '',
            'rtl'    => false
        ],
        'ru_BY'         => [
            'name'   => 'Russian (Belarus)',
            'native' => '',
            'rtl'    => false
        ],
        'ru_KZ'         => [
            'name'   => 'Russian (Kazakhstan)',
            'native' => '',
            'rtl'    => false
        ],
        'ru_KG'         => [
            'name'   => 'Russian (Kyrgyzstan)',
            'native' => '',
            'rtl'    => false
        ],
        'ru_MD'         => [
            'name'   => 'Russian (Moldova)',
            'native' => '',
            'rtl'    => false
        ],
        'ru_RU'         => [
            'name'   => 'Russian (Russia)',
            'native' => '',
            'rtl'    => false
        ],
        'ru_UA'         => [
            'name'   => 'Russian (Ukraine)',
            'native' => '',
            'rtl'    => false
        ],
        'sg'            => [
            'name'   => 'Sango',
            'native' => '',
            'rtl'    => false
        ],
        'sg_CF'         => [
            'name'   => 'Sango (Central African Republic)',
            'native' => '',
            'rtl'    => false
        ],
        'gd'            => [
            'name'   => 'Scottish Gaelic',
            'native' => '',
            'rtl'    => false
        ],
        'gd_GB'         => [
            'name'   => 'Scottish Gaelic (United Kingdom)',
            'native' => '',
            'rtl'    => false
        ],
        'sr'            => [
            'name'   => 'Serbian',
            'native' => '',
            'rtl'    => false
        ],
        'sr_BA'         => [
            'name'   => 'Serbian (Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Cyrl_BA'    => [
            'name'   => 'Serbian (Cyrillic, Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Cyrl_XK'    => [
            'name'   => 'Serbian (Cyrillic, Kosovo)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Cyrl_ME'    => [
            'name'   => 'Serbian (Cyrillic, Montenegro)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Cyrl_RS'    => [
            'name'   => 'Serbian (Cyrillic, Serbia)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Cyrl'       => [
            'name'   => 'Serbian (Cyrillic)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_XK'         => [
            'name'   => 'Serbian (Kosovo)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Latn_BA'    => [
            'name'   => 'Serbian (Latin, Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Latn_XK'    => [
            'name'   => 'Serbian (Latin, Kosovo)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Latn_ME'    => [
            'name'   => 'Serbian (Latin, Montenegro)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Latn_RS'    => [
            'name'   => 'Serbian (Latin, Serbia)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_Latn'       => [
            'name'   => 'Serbian (Latin)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_ME'         => [
            'name'   => 'Serbian (Montenegro)',
            'native' => '',
            'rtl'    => false
        ],
        'sr_RS'         => [
            'name'   => 'Serbian (Serbia)',
            'native' => '',
            'rtl'    => false
        ],
        'sh'            => [
            'name'   => 'Serbo-Croatian',
            'native' => '',
            'rtl'    => false
        ],
        'sh_BA'         => [
            'name'   => 'Serbo-Croatian (Bosnia & Herzegovina)',
            'native' => '',
            'rtl'    => false
        ],
        'sn'            => [
            'name'   => 'Shona',
            'native' => '',
            'rtl'    => false
        ],
        'sn_ZW'         => [
            'name'   => 'Shona (Zimbabwe)',
            'native' => '',
            'rtl'    => false
        ],
        'ii'            => [
            'name'   => 'Sichuan Yi',
            'native' => '',
            'rtl'    => false
        ],
        'ii_CN'         => [
            'name'   => 'Sichuan Yi (China)',
            'native' => '',
            'rtl'    => false
        ],
        'si'            => [
            'name'   => 'Sinhala',
            'native' => '',
            'rtl'    => false
        ],
        'si_LK'         => [
            'name'   => 'Sinhala (Sri Lanka)',
            'native' => '',
            'rtl'    => false
        ],
        'sk'            => [
            'name'   => 'Slovak',
            'native' => '',
            'rtl'    => false
        ],
        'sk_SK'         => [
            'name'   => 'Slovak (Slovakia)',
            'native' => '',
            'rtl'    => false
        ],
        'sl'            => [
            'name'   => 'Slovenian',
            'native' => '',
            'rtl'    => false
        ],
        'sl_SI'         => [
            'name'   => 'Slovenian (Slovenia)',
            'native' => '',
            'rtl'    => false
        ],
        'so'            => [
            'name'   => 'Somali',
            'native' => '',
            'rtl'    => false
        ],
        'so_DJ'         => [
            'name'   => 'Somali (Djibouti)',
            'native' => '',
            'rtl'    => false
        ],
        'so_ET'         => [
            'name'   => 'Somali (Ethiopia)',
            'native' => '',
            'rtl'    => false
        ],
        'so_KE'         => [
            'name'   => 'Somali (Kenya)',
            'native' => '',
            'rtl'    => false
        ],
        'so_SO'         => [
            'name'   => 'Somali (Somalia)',
            'native' => '',
            'rtl'    => false
        ],
        'es'            => [
            'name'   => 'Spanish',
            'native' => '',
            'rtl'    => false
        ],
        'es_AR'         => [
            'name'   => 'Spanish (Argentina)',
            'native' => '',
            'rtl'    => false
        ],
        'es_BO'         => [
            'name'   => 'Spanish (Bolivia)',
            'native' => '',
            'rtl'    => false
        ],
        'es_IC'         => [
            'name'   => 'Spanish (Canary Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'es_EA'         => [
            'name'   => 'Spanish (Ceuta & Melilla)',
            'native' => '',
            'rtl'    => false
        ],
        'es_CL'         => [
            'name'   => 'Spanish (Chile)',
            'native' => '',
            'rtl'    => false
        ],
        'es_CO'         => [
            'name'   => 'Spanish (Colombia)',
            'native' => '',
            'rtl'    => false
        ],
        'es_CR'         => [
            'name'   => 'Spanish (Costa Rica)',
            'native' => '',
            'rtl'    => false
        ],
        'es_CU'         => [
            'name'   => 'Spanish (Cuba)',
            'native' => '',
            'rtl'    => false
        ],
        'es_DO'         => [
            'name'   => 'Spanish (Dominican Republic)',
            'native' => '',
            'rtl'    => false
        ],
        'es_EC'         => [
            'name'   => 'Spanish (Ecuador)',
            'native' => '',
            'rtl'    => false
        ],
        'es_SV'         => [
            'name'   => 'Spanish (El Salvador)',
            'native' => '',
            'rtl'    => false
        ],
        'es_GQ'         => [
            'name'   => 'Spanish (Equatorial Guinea)',
            'native' => '',
            'rtl'    => false
        ],
        'es_GT'         => [
            'name'   => 'Spanish (Guatemala)',
            'native' => '',
            'rtl'    => false
        ],
        'es_HN'         => [
            'name'   => 'Spanish (Honduras)',
            'native' => '',
            'rtl'    => false
        ],
        'es_MX'         => [
            'name'   => 'Spanish (Mexico)',
            'native' => '',
            'rtl'    => false
        ],
        'es_NI'         => [
            'name'   => 'Spanish (Nicaragua)',
            'native' => '',
            'rtl'    => false
        ],
        'es_PA'         => [
            'name'   => 'Spanish (Panama)',
            'native' => '',
            'rtl'    => false
        ],
        'es_PY'         => [
            'name'   => 'Spanish (Paraguay)',
            'native' => '',
            'rtl'    => false
        ],
        'es_PE'         => [
            'name'   => 'Spanish (Peru)',
            'native' => '',
            'rtl'    => false
        ],
        'es_PH'         => [
            'name'   => 'Spanish (Philippines)',
            'native' => '',
            'rtl'    => false
        ],
        'es_PR'         => [
            'name'   => 'Spanish (Puerto Rico)',
            'native' => '',
            'rtl'    => false
        ],
        'es_ES'         => [
            'name'   => 'Spanish (Spain)',
            'native' => '',
            'rtl'    => false
        ],
        'es_US'         => [
            'name'   => 'Spanish (United States)',
            'native' => '',
            'rtl'    => false
        ],
        'es_UY'         => [
            'name'   => 'Spanish (Uruguay)',
            'native' => '',
            'rtl'    => false
        ],
        'es_VE'         => [
            'name'   => 'Spanish (Venezuela)',
            'native' => '',
            'rtl'    => false
        ],
        'sw'            => [
            'name'   => 'Swahili',
            'native' => '',
            'rtl'    => false
        ],
        'sw_KE'         => [
            'name'   => 'Swahili (Kenya)',
            'native' => '',
            'rtl'    => false
        ],
        'sw_TZ'         => [
            'name'   => 'Swahili (Tanzania)',
            'native' => '',
            'rtl'    => false
        ],
        'sw_UG'         => [
            'name'   => 'Swahili (Uganda)',
            'native' => '',
            'rtl'    => false
        ],
        'sv'            => [
            'name'   => 'Swedish',
            'native' => '',
            'rtl'    => false
        ],
        'sv_AX'         => [
            'name'   => 'Swedish (Åland Islands)',
            'native' => '',
            'rtl'    => false
        ],
        'sv_FI'         => [
            'name'   => 'Swedish (Finland)',
            'native' => '',
            'rtl'    => false
        ],
        'sv_SE'         => [
            'name'   => 'Swedish (Sweden)',
            'native' => '',
            'rtl'    => false
        ],
        'tl'            => [
            'name'   => 'Tagalog',
            'native' => '',
            'rtl'    => false
        ],
        'tl_PH'         => [
            'name'   => 'Tagalog (Philippines)',
            'native' => '',
            'rtl'    => false
        ],
        'ta'            => [
            'name'   => 'Tamil',
            'native' => '',
            'rtl'    => false
        ],
        'ta_IN'         => [
            'name'   => 'Tamil (India)',
            'native' => '',
            'rtl'    => false
        ],
        'ta_MY'         => [
            'name'   => 'Tamil (Malaysia)',
            'native' => '',
            'rtl'    => false
        ],
        'ta_SG'         => [
            'name'   => 'Tamil (Singapore)',
            'native' => '',
            'rtl'    => false
        ],
        'ta_LK'         => [
            'name'   => 'Tamil (Sri Lanka)',
            'native' => '',
            'rtl'    => false
        ],
        'te'            => [
            'name'   => 'Telugu',
            'native' => '',
            'rtl'    => false
        ],
        'te_IN'         => [
            'name'   => 'Telugu (India)',
            'native' => '',
            'rtl'    => false
        ],
        'th'            => [
            'name'   => 'Thai',
            'native' => '',
            'rtl'    => false
        ],
        'th_TH'         => [
            'name'   => 'Thai (Thailand)',
            'native' => '',
            'rtl'    => false
        ],
        'bo'            => [
            'name'   => 'Tibetan',
            'native' => '',
            'rtl'    => false
        ],
        'bo_CN'         => [
            'name'   => 'Tibetan (China)',
            'native' => '',
            'rtl'    => false
        ],
        'bo_IN'         => [
            'name'   => 'Tibetan (India)',
            'native' => '',
            'rtl'    => false
        ],
        'ti'            => [
            'name'   => 'Tigrinya',
            'native' => '',
            'rtl'    => false
        ],
        'ti_ER'         => [
            'name'   => 'Tigrinya (Eritrea)',
            'native' => '',
            'rtl'    => false
        ],
        'ti_ET'         => [
            'name'   => 'Tigrinya (Ethiopia)',
            'native' => '',
            'rtl'    => false
        ],
        'to'            => [
            'name'   => 'Tongan',
            'native' => '',
            'rtl'    => false
        ],
        'to_TO'         => [
            'name'   => 'Tongan (Tonga)',
            'native' => '',
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
