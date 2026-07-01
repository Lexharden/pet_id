<?php

declare(strict_types=1);

namespace App\Actions\Scans;

use App\Enums\ScanChannel;
use App\Enums\ScanResult;
use App\Models\Pet;
use App\Models\ScanEvent;
use App\Models\Tag;
use App\Support\PublicPetProfile;
use Illuminate\Http\Request;

final class ResolvePublicTag
{
    public function __construct(
        private RecordScan $recordScan,
    ) {}

    /**
     * @return array{tag: ?Tag, profile: ?array<string, mixed>, result: ScanResult, scan: ?ScanEvent}
     */
    public function execute(string $publicToken, Request $request): array
    {
        $tag = Tag::query()
            ->where('public_token', $publicToken)
            ->with(['activeAssignment.pet.publicSettings', 'activeAssignment.pet.emergencySummary', 'activeAssignment.pet.contacts'])
            ->first();

        if (! $tag) {
            return [
                'tag' => null,
                'profile' => null,
                'result' => ScanResult::NotFound,
                'scan' => null,
            ];
        }

        $channel = ScanChannel::fromRequest($request->query('c'));
        $result = $this->recordScan->resolveResult($tag);
        $scan = $this->recordScan->execute($tag, $channel, $result, $request);

        if ($result !== ScanResult::ProfileViewed) {
            return [
                'tag' => $tag,
                'profile' => null,
                'result' => $result,
                'scan' => $scan,
            ];
        }

        $pet = $tag->activeAssignment?->pet;

        if (! $pet instanceof Pet) {
            return [
                'tag' => $tag,
                'profile' => null,
                'result' => $result,
                'scan' => $scan,
            ];
        }

        return [
            'tag' => $tag,
            'profile' => PublicPetProfile::build($pet),
            'result' => $result,
            'scan' => $scan,
        ];
    }
}
