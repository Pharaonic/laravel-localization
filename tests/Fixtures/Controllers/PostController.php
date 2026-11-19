<?php

declare(strict_types=1);

namespace Pharaonic\Laravel\Localization\Tests\Fixtures\Controllers;

use Illuminate\Http\JsonResponse;
use Pharaonic\Laravel\Localization\Tests\Fixtures\Models\Post;

final class PostController
{
    public function show(Post $post): JsonResponse
    {
        return new JsonResponse([
            'id' => $post->id,
            'locale' => app()->getLocale(),
            'alternates' => locale()->alternates(),
            'hreflangs' => locale()->hreflangs(),
            'switch' => [
                'ar' => locale()->switchUrl('ar'),
                'fr' => locale()->switchUrl('fr'),
                'en' => locale()->switchUrl('en'),
            ],
        ]);
    }
}
