@extends('backend.admin.master')

@section('content')
    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <!--breadcrumb-->
                @include('backend.section.breadcrumb', [
                    'title' => 'Thống kê completion theo course',
                    'sub_title' => 'Thống kê completion theo course',
                ])
                <!--end breadcrumb-->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Số học viên</th>
                            <th>Avg completion</th>
                            <th>Hoàn thành 100%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courseStats as $item)
                            <tr>
                                <td>{{ $item->course?->course_title }}</td>
                                <td>{{ $item->enrolled_users }}</td>
                                <td>{{ round($item->avg_completion, 2) }}%</td>
                                <td>{{ $item->completed_users }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h4>Thống kê theo user</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Course</th>
                            <th>Completion</th>
                            <th>Last Lecture</th>
                            <th>Last Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userStats as $row)
                            <tr>
                                <td>{{ $row->user?->name }}</td>
                                <td>{{ $row->course?->course_title }}</td>
                                <td>{{ $row->completion_percent }}%</td>
                                <td>{{ $row->lastLecture?->lecture_title ?? '-' }}</td>
                                <td>{{ $row->last_activity_at ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $userStats->links() }}
            </div>
        </div>
    </div>
@endsection
