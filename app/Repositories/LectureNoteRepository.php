<?php

namespace App\Repositories;

use App\Models\LectureNote;

class LectureNoteRepository
{
    public function getByLectureAndUser(int $lectureId, int $userId, int $perPage = 20)
    {
        return LectureNote::where('lecture_id', $lectureId)
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): LectureNote
    {
        return LectureNote::create($data);
    }

    public function findUserNote(int $id, int $userId): ?LectureNote
    {
        return LectureNote::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function update(LectureNote $note, array $data): bool
    {
        return $note->update($data);
    }

    public function delete(LectureNote $note): bool
    {
        return (bool) $note->delete();
    }
}
