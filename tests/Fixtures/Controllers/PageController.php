<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

final class PageController
{
    public function __invoke(Request $request): JsonResponse
    {
        $route = $request->route();

        return new JsonResponse([
            'locale' => app()->getLocale(),
            'current' => locale()->current(),
            'source' => locale()->source(),
            'mode' => locale()->mode(),
            'route' => $route instanceof Route ? $route->getName() : null,
            'parameters' => $route instanceof Route ? array_map(fn ($value) => is_object($value) ? $value::class : $value, $route->parameters()) : [],
            'translated' => trans('messages.hello'),
        ]);
    }
}
