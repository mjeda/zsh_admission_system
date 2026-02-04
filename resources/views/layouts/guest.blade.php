<!doctype html>
<html lang="en">
<head>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>ZSH - Student Admission System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Custom CSS -->
    <style>
        .bg-image {
            background-image: url({{asset("images/students.jpeg")}});
            background-size: cover; /* Cover the entire area */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent image repetition */
            height: 100%; /* Full viewport height */
            width: 100%; /* Full width */
        }
    </style>
    @livewireStyles
</head>
<body style="background-color: #05999e">
<section class="ftco-section bg-image">
    @yield("content") {{ $slot ?? '' }}
</section>


<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/popper.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"/>
<script>
    jQuery("#index_number").inputmask({"mask": "S9999/9999/9999"});
</script>
@livewireScripts
</body>
</html>
