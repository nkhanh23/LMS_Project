<?php

namespace App\Services;

use App\Repositories\LectureNoteRepository;

class LectureNoteService
{
    protected $noteRepository;

    public function __construct(LectureNoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function getByLectureAndUser(int $lectureId, int $userId, int $perPage = 20)
    {
        return $this->noteRepository->getByLectureAndUser($lectureId, $userId, $perPage);
    }

    public function store(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        $data['formatted_time'] = gmdate($data['video_second'] >= 3600 ? 'H:i:s' : 'i:s', $data['video_second']);

        $note = $this->noteRepository->create($data);

        return [
            'status' => 'success',
            'note' => $note->load('user'),
        ];
    }

    public function update(int $id, array $data, int $userId)
    {
        $note = $this->noteRepository->findUserNote($id, $userId);

        if (!$note) {
            return [
                'status' => 'error',
                'message' => 'Không tìm thấy ghi chú.',
                'code' => 404,
            ];
        }

        $this->noteRepository->update($note, $data);

        return [
            'status' => 'success',
            'note' => $note->load('user'),
        ];
    }

    public function delete(int $id, int $userId)
    {
        $note = $this->noteRepository->findUserNote($id, $userId);

        if (!$note) {
            return [
                'status' => 'error',
                'message' => 'Không tìm thấy ghi chú.',
                'code' => 404,
            ];
        }

        $this->noteRepository->delete($note);

        return [
            'status' => 'success',
            'message' => 'Đã xóa ghi chú.',
        ];
    }
}
