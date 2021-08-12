<?php

namespace Pharaonic\Laravel\Localization\Classes;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

/**
 *  Laravel URL Localization Manager
 *  ccTLDs, sub-domains, sub-directories
 *
 * @version 1.0.0
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
class Localization
{
    /**
     * Localization type.
     * 1. ccTLDs
     * 2. sub-domain
     * 3. sub-directory (default)
     *
     * @var string
     */
    private $type = 'sub-directory';

    /**
     * Current locale.
     *
     * @var string
     */
    public $current;

    /**
     * Temp locale.
     *
     * @var string|null
     */
    private $tmp;

    /**
     * Default locale.
     *
     * @var string
     */
    public $default;

    /**
     * Allow to hide the default locale.
     *
     * @var bool
     */
    private $hideDefault = false;

    /**
     * Force redirect to default localization.
     *
     * @var bool
     */
    private $forceRedirect = false;

    /**
     * List of supported locales.
     *
     * @var array
     */
    public $supported = [];

    /**
     * List of all locales.
     *
     * @var array
     */
    private $list = [];

    /**
     * List of ccTLDs.
     *
     * @var array
     */
    private $ccTLDs_list = [];


    /**
     * Create a new instance
     */
    public function __construct()
    {
        // Load Configrations
        $config = config('Pharaonic.localization', config('laravel-localization'));
        $this->type             = $config['type'];
        $this->default          = $config['default'];
        $this->hideDefault      = $config['hide-default'];
        $this->forceRedirect    = $config['force-redirect'];
        $this->supported        = $config['supported'];
        $this->list             = $config['list'];
        $this->ccTLDs_list      = $config['ccTLDs-list'];

        $this->setlocale($this->current = $this->default ?? app()->getLocale());
    }

    /**
     * Detect ccTLDs.
     *
     * @param Request $request
     * @param callable $routes
     * @return mixed
     */
    private function ccTLDs(Request $request, callable $routes)
    {
        $domain = explode('.', $request->getHost());
        $cc = last($domain);

        if ($this->isSupportedccTLD($cc)) {

            $this->setLocale($this->ccTLDs_list[$cc]);
            return $routes();
        } else {
            throw new Exception('This ccTLD has not been supported yet.');
        }
    }

    /**
     * Detect Sub-domain
     *
     * @param Request $request
     * @param callable $routes
     * @return mixed
     */
    private function sub_domain(Request $request, callable $routes)
    {
        $envURL = parse_url(str_replace('wwww.', '', env('APP_URL', 'http://127.0.0.1')));

        Route::domain('{locale?}'  . $envURL['host'])->where(['locale' => '([a-zA-Z]+\.)?'])->group(function () use ($routes) {
            $routes();
        });

        if (strpos($request->getHost(), $envURL['host']) === false) {
            throw new Exception('This is a wrong domain, you have to put the right APP_URL in env file.');
        } else {
            $sub_domain = explode('.', trim(substr($request->getHost(), 0, -1 * strlen($envURL['host'])), '.'));
            $sub_domain = empty($sub_domain) ? null : ($this->isSupportedLocale(last($sub_domain)) ? last($sub_domain) : null);

            if (!$sub_domain && !$this->hideDefault) {
                if ($this->forceRedirect) {
                    return Redirect::to(rtrim($request->getScheme() . '://' . $this->default . '.' . $envURL['host'] . $request->getRequestUri(), '/'))->send();
                } else {
                    return abort(404);
                }
            } elseif ($sub_domain) {
                if ($sub_domain == $this->default && $this->hideDefault)
                    return Redirect::to(rtrim(env('APP_URL'), '/') . $request->getRequestUri())->send();

                $this->setLocale($sub_domain);
            }
        }
    }

    /**
     * Detect Sub-Directory
     *
     * @param Request $request
     * @param callable $routes
     * @return mixed
     */
    private function sub_directory(Request $request, callable $routes)
    {
        Route::prefix('/{locale?}')->group(function () use ($routes) {
            $routes();
        });

        $uri = array_values(array_filter(explode('/', request()->getRequestUri())));
        $localized = isset($uri[0]) && $this->isSupportedLocale($uri[0]) ? $uri[0] : null;

        if (!$localized && !$this->hideDefault) {
            if ($this->forceRedirect) {
                return Redirect::to(rtrim($request->getScheme() . '://' . $request->getHost() . ($request->getPort() ? ':' . $request->getPort() : null) . '/' . $this->default . $request->getRequestUri(), '/'))->send();
            } else {
                return abort(404);
            }
        }

        if ($localized) {
            if ($localized == $this->default && $this->hideDefault) {
                unset($uri[0]);
                return Redirect::to(implode('/', $uri))->send();
            } else {
                $this->setLocale($localized);
            }
        }
    }

    /**
     * Detect the locale
     *
     * @param mixed $routes
     * @return void
     */
    public function detect($routes)
    {
        // FOR CONSOLE
        if (app()->runningInConsole()) {
            switch ($this->type) {
                case 'sub-directory':
                    Route::prefix('/{locale?}')->group(function () use ($routes) {
                        $routes();
                    });
                    break;
                default:
                    $routes();
                    break;
            }
            return;
        }

        // FOR WEB
        $request    = request();
        return $this->{str_replace('-', '_', $this->type)}($request, $routes);
    }

    /**
     * Check if locale has been supported or not
     *
     * @param string $locale
     * @return boolean
     */
    public function isSupportedLocale(string $locale)
    {
        return isset($this->list[$locale]) && in_array($locale, $this->supported);
    }

    /**
     * Check if ccTLD has been supported or not
     *
     * @param string $ccTLD
     * @return boolean
     */
    public function isSupportedccTLD(string $ccTLD)
    {
        $locale = $this->ccTLDs_list[$ccTLD] ?? null;
        return isset($this->ccTLDs_list[$ccTLD]) && $this->isSupportedLocale($locale);
    }

    /**
     * Setting Locale.
     *
     * @param string $locale
     * @return void
     */
    public function setLocale(string $locale)
    {
        if (!$this->isSupportedLocale($locale))
            throw new Exception('This locale has not been supported yet.');

        $this->current = $locale;
        app()->setlocale($locale);

        // TASK : SESSION [NEXT VERSION].
    }

    /**
     * Setting Temp Locale
     *
     * @param string $locale
     * @return void
     */
    public function setTempLocale(string $locale = null)
    {
        if ($locale && !$this->isSupportedLocale($locale))
            throw new Exception('This locale has not been supported yet.');

        $this->tmp = $locale;

        return $this;
    }

    /**
     * Getting locale name.
     *
     * @param string|null $locale
     * @return string
     */
    public function getName(string $locale = null)
    {
        return $this->list[$locale ?? $this->current]['name'];
    }

    /**
     * Getting locale native name.
     *
     * @param string|null $locale
     * @return string
     */
    public function getNativeName(string $locale = null)
    {
        return $this->list[$locale ?? $this->current]['native'];
    }

    /**
     * Getting locale abbreviation.
     *
     * @param string|null $locale
     * @return string
     */
    public function getAbbreviation(string $locale = null)
    {
        return $this->list[$locale ?? $this->current]['abbr'];
    }

    /**
     * Getting locale direction.
     *
     * @param string|null $locale
     * @return string
     */
    public function getDirection(string $locale = null)
    {
        return $this->isRTL($locale) ? 'rtl' : 'ltr';
    }

    /**
     * Check if RTL locale.
     *
     * @param string|null $locale
     * @return bool
     */
    public function isRTL(string $locale = null)
    {
        return $this->list[$locale ?? $this->current]['rtl'] == true;
    }

    /**
     * Get localized route
     *
     * @param string $key
     * @param array $params
     * @return string
     */
    public function route(string $key, array $params = [])
    {
        $locale = $this->tmp ?? $this->current;

        if ($locale == $this->default && $this->hideDefault)
            return route($key, $params);

        return route($key, array_merge(['locale' => $locale . ($this->type == 'sub-domain' ? '.' : null)], $params));
    }

    /**
     * Get un-localized route
     *
     * @param string $key
     * @param array $params
     * @return string
     */
    public function unLocalizedRoute(string $key, array $params = [])
    {
        return route($key, $params);
    }
}
