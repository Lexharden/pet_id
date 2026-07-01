<?php

declare(strict_types=1);

namespace App\Actions\Scans;

use App\Enums\ScanChannel;
use App\Enums\ScanResult;
use App\Enums\TagStatus;
use App\Models\ScanEvent;
use App\Models\Tag;
use App\Support\IpHasher;
use Illuminate\Http\Request;

final class RecordScan
{
    public function execute(Tag $tag, ScanChannel $channel, ScanResult $result, ?Request $request = null): ScanEvent
    {
        $userAgent = $request?->userAgent();
        if ($userAgent && strlen($userAgent) > 255) {
            $userAgent = substr($userAgent, 0, 255);
        }

        return ScanEvent::query()->create([
            'tag_id' => $tag->id,
            'channel' => $channel,
            'scanned_at' => now(),
            'ip_hash' => IpHasher::hash($request?->ip()),
            'user_agent' => $userAgent,
            'result' => $result,
        ]);
    }

    public function resolveResult(Tag $tag): ScanResult
    {
        return match ($tag->status) {
            TagStatus::Activated => $tag->activeAssignment
                ? ScanResult::ProfileViewed
                : ScanResult::Unassigned,
            TagStatus::Manufactured => ScanResult::Unassigned,
            TagStatus::Disabled, TagStatus::Retired => ScanResult::Disabled,
            TagStatus::Replaced => ScanResult::Disabled,
        };
    }
}
