<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Scans\ResolvePublicTag;
use App\Enums\ScanResult;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PublicTagController extends Controller
{
    public function __invoke(string $token, Request $request, ResolvePublicTag $resolvePublicTag): View
    {
        $resolved = $resolvePublicTag->execute($token, $request);

        if ($resolved['result'] === ScanResult::NotFound) {
            abort(404);
        }

        if ($resolved['result'] !== ScanResult::ProfileViewed) {
            return view('public.tag-unavailable', [
                'result' => $resolved['result'],
            ]);
        }

        return view('public.tag-profile', [
            'profile' => $resolved['profile'],
            'tag' => $resolved['tag'],
            'scan' => $resolved['scan'],
        ]);
    }
}
