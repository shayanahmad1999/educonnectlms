<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EDU CONNECT LMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container p-4 mt-4">
        <h1 class="text-center">Welcome to Our Challenge Strategies for Data Retrieval</h1>
        <div class="container text-center mt-4">
            <div class="row">
                <div class="col-3">
                    <a href="{{ route('toStudents') }}">
                        <button type="button" class="btn btn-outline-primary">Top Performing Students</button>
                    </a>
                </div>
                <div class="col-3">
                    <a href="{{ route('courseAnalytics') }}">
                        <button type="button" class="btn btn-outline-primary">Course Engagement Analytics</button>
                    </a>
                </div>
                <div class="col-3">
                    <a href="{{ route('dualRoleUsers') }}">
                        <button type="button" class="btn btn-outline-primary">Cross-role Filtering</button>
                    </a>
                </div>
                <div class="col-3">
                    <a href="{{ route('passiveParticipantsPulse') }}">
                        <button type="button" class="btn btn-outline-primary">Passive Participants Pulse</button>
                    </a>
                </div>
                <div class="row mt-4">
                    <div class="col-3">
                        <a href="{{ route('assignmentLoadHeatmap') }}">
                            <button type="button" class="btn btn-outline-primary">Assignment Load Heatmap</button>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('gradeConsistencyChecker') }}">
                            <button type="button" class="btn btn-outline-primary">Grade Consistency Checker</button>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('submissionBlackHoles') }}">
                            <button type="button" class="btn btn-outline-primary">Submission Black Holes</button>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('parallelSubmissions') }}">
                            <button type="button" class="btn btn-outline-primary">Parallel Submissions</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
