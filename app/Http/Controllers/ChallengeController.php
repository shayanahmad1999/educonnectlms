<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Role;
use App\Models\Submission;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ChallengeController extends Controller
{
    protected function displayError($status, $data, $code)
    {
        return response()->json([
            'status' => $status,
            'result' => $data
        ], $code, [], JSON_PRETTY_PRINT);
    }

    public function topStudents()
    {
        try {
            $query = User::query()
                ->withAvg('submissions as avg_grade', 'grade')
                ->orderByDesc('avg_grade')
                ->take(5);

            // dd($query->toSql());

            $topStudents = $query->get();
            return $this->displayError('success', $topStudents, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function topCourses(Request $request, int $days = 30): JsonResponse
    {
        try {
            // $baseQuery = $request->user()->isAdmin()
            //     ? Course::query()
            //     : $request->user()->taughtCourses();   
            $baseQuery =  Course::query();

            $courses = $baseQuery
                ->mostActive($days)
                ->take(10)
                ->get(['id', 'name', 'submission_count']);

            return $this->displayError('success', $courses, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function dualRoleUsers()
    {
        try {
            $dualRoleUsers = User::dualRole()->get();
            return $this->displayError('success', $dualRoleUsers, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function passiveParticipantsPulse(): JsonResponse
    {
        try {
            $query = User::whereHas('role', fn($q) => $q->where('name', 'Student'))
                ->withCount('courses')
                ->having('courses_count', '>=', 5)
                ->whereDoesntHave('submissions', fn($q) =>
                $q->where('submitted_at', '>=', now()->subDays(90)))
                ->whereDoesntHave('notifications', fn($q) =>
                $q->where('created_at', '>=', now()->subDays(90)))
                ->get();
            return $this->displayError('success', $query, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function assignmentLoadHeatmap(): JsonResponse
    {
        try {
            $query = Assignment::select('course_id')
                ->selectRaw('YEARWEEK(due_date, 1) as week_number')
                ->whereBetween('due_date', [now(), now()->addMonths(3)])
                ->groupBy('course_id', 'week_number')
                ->selectRaw('COUNT(*) as count')
                ->get()
                ->groupBy('course_id'); // Ready for heatmap UI
            return $this->displayError('success', $query, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function gradeConsistencyChecker(): JsonResponse
    {
        try {
            $query = Course::with([
                'submissions' => fn($q) =>
                $q->selectRaw('assignment_id, assignments.course_id, STDDEV_POP(grade) as std_dev')
                    ->groupBy('assignment_id', 'assignments.course_id')
            ])
                ->get()
                ->filter(
                    fn($course) =>
                    $course->submissions->avg('std_dev') > 20
                );

            return $this->displayError('success', $query, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function submissionBlackHoles(): JsonResponse
    {
        try {
            $query = Assignment::whereDoesntHave('submissions')
                ->whereHas(
                    'course',
                    fn($q) =>
                    $q->whereHas(
                        'students',
                        fn($q2) =>
                        $q2->select('user_id')
                            ->groupBy('user_id')
                            ->havingRaw('COUNT(*) >= 10')
                    )
                )
                ->get();

            return $this->displayError('success', $query, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }

    public function parallelSubmissions()
    {
        try {
            $query = Submission::query()
                ->with('student:id,id,name')
                ->select('student_id', 'submitted_at')
                ->groupBy('student_id', 'submitted_at')
                ->having(DB::raw('COUNT(*)'), '>', 1)
                ->get();

            $filename = 'parallel_submissions.json';

            return Response::make($query->toJson(JSON_PRETTY_PRINT), 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename={$filename}"
            ]);


            return $this->displayError('success', $query, 200);
        } catch (Exception $e) {
            return $this->displayError('error', $e->getMessage(), 402);
        }
    }
}
