<p align="center"><a href="https://pharaonic.io" target="_blank"><img src="https://raw.githubusercontent.com/Pharaonic/logos/main/localization.jpg"></a></p>

<p align="center">
  <a href="https://php.net" target="_blank"><img src="https://img.shields.io/static/v1?label=PHP&message=%3E=7.2&color=blue&style=flat-square" alt="PHP Version : >= 7.2"></a>
  <a href="https://laravel.com" target="_blank"><img src="https://img.shields.io/static/v1?label=Laravel&message=%3E=6.0&color=F05340&style=flat-square" alt="Laravel Version : >= 6.0"></a>
  <img src="https://img.shields.io/static/v1?label=License&message=MIT&color=brightgreen&style=flat-square" alt="License">
  <a href="https://liberapay.com/Pharaonic" target="_blank"><img src="https://img.shields.io/liberapay/receives/Pharaonic?color=gold&label=Support&style=flat-square" alt="Support"></a>
  <br>
  <a href="https://packagist.org/packages/Pharaonic/laravel-localization" target="_blank"><img src="https://img.shields.io/static/v1?label=Packagist&message=pharaonic/laravel-localization&color=blue&logo=packagist&logoColor=white" alt="Source"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-localization" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-localization/v" alt="Packagist Version"></a>
  <a href="https://packagist.org/packages/pharaonic/laravel-localization" target="_blank"><img src="https://poser.pugx.org/pharaonic/laravel-localization/downloads" alt="Packagist Downloads"></a>
</p>

<h3 align="center">Laravel URL Localization - (ccTLD, sub-domain, sub-directory).</h3>
<h5 align="center">with Simple & Easy Helpers.</h5>

`Afrikaans` `Akan` `shqip` `አማርኛ` `العربية` `հայերեն` `অসমীয়া` `azərbaycan` `Азәрбајҹан` `bamanakan` `euskara` `беларуская` `বাংলা` `босански` `bosanski` `brezhoneg` `български` `ဗမာ` `català` `中文` `简体中文` `繁体中文` `kernewek` `hrvatski` `čeština` `dansk` `Nederlands` `རྫོང་ཁ` `English` `Esperanto` `eesti` `eʋegbe` `føroyskt` `suomi` `français` `Pulaar` `galego` `Luganda` `ქართული` `Deutsch` `Ελληνικά` `ગુજરાતી` `Hausa` `עברית` `हिन्दी` `magyar` `íslenska` `Igbo` `Bahasa Indonesia` `Gaeilge` `italiano` `日本語` `kalaallisut` `ಕನ್ನಡ` `کٲشُر` `қазақ тілі` `ខ្មែរ` `Gikuyu` `Kinyarwanda` `한국어` `кыргызча` `ລາວ` `latviešu` `lingála` `lietuvių` `Tshiluba` `Lëtzebuergesch` `македонски` `Malagasy` `Bahasa Melayu` `മലയാളം` `Malti` `Gaelg` `मराठी` `монгол` `नेपाली` `isiNdebele` `davvisámegiella` `norsk` `norsk bokmål` `nynorsk` `ଓଡ଼ିଆ` `Oromoo` `ирон` `پښتو` `فارسی` `polski` `português` `پنجابی` `ਪੰਜਾਬੀ` `Runasimi` `română` `rumantsch` `Ikirundi` `русский` `Sängö` `Gàidhlig` `српски` `srpski` `Srpskohrvatski` `chiShona` `ꆈꌠꉙ` `සිංහල` `slovenčina` `slovenščina` `Soomaali` `español` `Kiswahili` `svenska` `Tagalog` `தமிழ்` `తెలుగు` `ไทย` `བོད་སྐད་` `ትግርኛ` `lea fakatonga` `Türkçe` `українська` `اردو` `ئۇيغۇرچە` `oʻzbekcha` `Tiếng Việt` `Cymraeg` `West-Frysk` `ייִדיש` `Èdè Yorùbá` `isiZulu`
<br>
<h6>Sub-Directory Localzation Routing Example :</h6>



````php
Route::localized(function(){
    Route::get('/page', function(){
        return 'Pharaonic';
    });
});

// Output : {locale?}/page
````

## Documentation

You can find the detailed documentation here in [Laravel Localization Documentation](https://pharaonic.io/package/2-laravel/23-localization).

## Contributing

Thank you for considering contributing to this package! Be one of Pharaonic team.

## Pharaonic Sponsors

We would like to extend our thanks to the following sponsors for funding Pharaonic packages development. If you are interested in becoming a sponsor, please visit the Pharaonic [Liberapay page](https://en.liberapay.com/Pharaonic) or [Patreon page](https://patreon.com/Pharaonic).

## License

This package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
