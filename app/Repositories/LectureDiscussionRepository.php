<?php

namespace App\Repositories;

use App\Models\LectureDiscussion;

class LectureDiscussionRepository
{
    public function getByLecture(int $lectureId, int $perPage = 10)
    {
        return LectureDiscussion::with(['user', 'replies'])
            ->where('lecture_id', $lectureId)
            ->whereNull('parent_id')
            ->where('is_approved', true)
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): LectureDiscussion
    {
        return LectureDiscussion::create($data);
    }

    public function findById(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::with(['user', 'parent'])->find($id);
    }

    public function findRootById(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::whereNull('parent_id')->find($id);
    }

    public function findByIdWithTrashed(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::withTrashed()->find($id);
    }

    public function delete(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->delete();
    }

    public function restore(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->restore();
    }

    public function forceDelete(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->forceDelete();
    }
}
