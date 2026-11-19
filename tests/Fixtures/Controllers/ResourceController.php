<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers;

use Illuminate\Http\JsonResponse;

final class ResourceController
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['action' => 'index', 'locale' => app()->getLocale()]);
    }

    public function show(string $product): JsonResponse
    {
        return new JsonResponse(['action' => 'show', 'product' => $product, 'locale' => app()->getLocale()]);
    }

    public function store(): JsonResponse
    {
        return new JsonResponse(['action' => 'store', 'locale' => app()->getLocale()], 201);
    }

    public function update(string $product): JsonResponse
    {
        return new JsonResponse(['action' => 'update', 'product' => $product, 'locale' => app()->getLocale()]);
    }

    public function destroy(string $product): JsonResponse
    {
        return new JsonResponse(['action' => 'destroy', 'product' => $product, 'locale' => app()->getLocale()]);
    }
}
