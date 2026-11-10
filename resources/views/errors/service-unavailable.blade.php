<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">
    <title>{{ $service ?? 'NECTA/NACTE' }} haipatikani - ZSH Admission</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body { background: #f4f6f9; min-height: 100vh; display: flex; align-items: center; }
        .su-card { max-width: 560px; margin: 0 auto; border: 0; border-radius: 12px; box-shadow: 0 6px 24px rgba(0,0,0,.08); }
        .su-icon { width: 72px; height: 72px; border-radius: 50%; background: #fdecea; color: #dc3545;
                   display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px; }
        .su-action { background: #f8f9fa; border-left: 4px solid #dc3545; padding: 10px 14px; border-radius: 4px; }
        .su-contact a { text-decoration: none; }
    </style>
</head>
<body>
@php
    $service = $service ?? 'NECTA/NACTE';
    $action = $action ?? 'kukamilisha ombi lako';
    $email = config('app.admission_email');
    $phone = config('app.admission_phone');
    $back = url()->previous() !== url()->current() ? url()->previous() : url('/');
@endphp
<div class="container px-3 py-5">
    <div class="card su-card">
        <div class="card-body p-4 p-md-5 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="" height="56" class="mb-3" onerror="this.remove()">

            <div class="su-icon"><i class="fa fa-plug"></i></div>

            <h1 class="h4 mb-2">Mfumo wa {{ $service }} haupatikani kwa sasa</h1>
            <p class="text-muted mb-4">{{ $service }} system is currently unavailable</p>

            <div class="su-action text-left mb-4">
                <strong>Tumeshindwa:</strong> {{ $action }}.
                <div class="small text-muted mt-1">
                    Hakuna kilichobadilishwa. Tatizo liko kwenye mfumo wa {{ $service }}, si kwenye taarifa zako.
                </div>
            </div>

            <p class="mb-4">
                Tafadhali <strong>jaribu tena baada ya muda mfupi</strong>.
                Tatizo likiendelea, wasiliana na <strong>Admission team</strong>.
            </p>

            @if($email || $phone)
                <div class="su-contact mb-4">
                    @if($phone)
                        <div><i class="fa fa-phone mr-1"></i> <a href="tel:{{ $phone }}">{{ $phone }}</a></div>
                    @endif
                    @if($email)
                        <div><i class="fa fa-envelope mr-1"></i> <a href="mailto:{{ $email }}">{{ $email }}</a></div>
                    @endif
                </div>
            @endif

            <div class="d-flex justify-content-center flex-wrap">
                <a href="{{ $back }}" class="btn btn-primary m-1">
                    <i class="fa fa-refresh"></i> Jaribu tena
                </a>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary m-1">
                    <i class="fa fa-home"></i> Rudi mwanzo
                </a>
            </div>

            <p class="small text-muted mt-4 mb-0">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>
</body>
</html>
