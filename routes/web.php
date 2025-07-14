<?php

use App\Http\Controllers\ChallengeController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::controller(ChallengeController::class)->prefix('challenge')->group(function () {
    Route::get('top-students', 'topStudents')->name('toStudents');
    Route::get('course-analytics', 'topCourses')->name('courseAnalytics');
    Route::get('dual-role-users', 'dualRoleUsers')->name('dualRoleUsers');


    Route::get('passive-participants-pulse', 'passiveParticipantsPulse')->name('passiveParticipantsPulse');
    Route::get('assignment-load-heatmap', 'assignmentLoadHeatmap')->name('assignmentLoadHeatmap');
    Route::get('grade-consistency-checker', 'gradeConsistencyChecker')->name('gradeConsistencyChecker');
    Route::get('submission-black-holes', 'submissionBlackHoles')->name('submissionBlackHoles');
    Route::get('parallel-submissions', 'parallelSubmissions')->name('parallelSubmissions');
});
