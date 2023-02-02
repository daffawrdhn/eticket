<!DOCTYPE html>
<html>
<head>
    <title>Git Pull Output</title>
</head>
<body>
    <h1>Git Pull Output</h1>

    <pre>
        {{ implode("\n", $pullOutput) }}
    </pre>

    <h1>Git Log Output</h1>

    <pre>
        {{ implode("\n", $logOutput) }}
    </pre>

    <a href="{{ route('pull') }}" class="btn btn-primary">Git Pull</a>
</body>
</html>
