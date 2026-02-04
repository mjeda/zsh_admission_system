<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>ZSH - Student Admission System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="container ">
        <h1>An error occurred</h1>
        <p>{{ $error }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary">
            <i class="fa fa-backward"></i> Go Back</a>
    </div>
</body>
</html>