<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Completion</title>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; }
        .border { border: 8px solid #000; padding: 40px; }
        h1 { font-size: 36px; margin-bottom: 0; }
        h2 { margin-top: 5px; font-size: 24px; }
        p { font-size: 18px; }
    </style>
</head>
<body>
    <div class="border">
        <h1>Certificate of Completion</h1>
        <p>This is to certify that</p>
        <h2>{{ $student->name }}</h2>
        <p>has successfully completed the course</p>
        <h2>{{ $course->title }}</h2>
        <p>Date: {{ $date }}</p>
    </div>
</body>
</html>
