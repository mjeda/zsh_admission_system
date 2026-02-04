@extends('layouts.app')

@push('style')
    <style>
        /* Background Image with Transparent Overlay */
        .bg-image {
            background-image: url({{ asset('images/students.jpeg') }});
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 93vh; /* Full viewport height */
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Semi-transparent overlay */
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Adjust transparency */
            z-index: 1;
        }

        /* Content should be above the overlay */
        .content-wrapper {
            position: relative;
            z-index: 2;
            width: 100%;
            text-align: center;
        }

        /* Semi-transparent welcome section */
        .welcome-section {
            width: 100%;
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.2); /* Adjust transparency */
            backdrop-filter: blur(10px); /* Smooth blur effect */
            border-radius: 10px;
            color: white;
        }

        /* Card container */
        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            margin-top: 20px;
        }

        /* Cards with transparency */
        .card {
            width: 300px;
            text-align: center;
            border-radius: 10px;
            padding: 20px;
            color: white;
            backdrop-filter: blur(10px); /* Smooth blur effect */
            background: rgba(255, 255, 255, 0.2); /* Adjust transparency */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .nta-card {
            border: 2px solid #007bff;
        }

        .nta-card:hover {
            color: #007bff;
        }

        .nva-card {
            border: 2px solid #28a745;
        }

        .nva-card:hover {
            color: #28a745;
        }

        .status {
            font-weight: bold;
            font-size: 18px;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="bg-image">
        <!-- Transparent Overlay -->
        <div class="bg-overlay"></div>

        <!-- Content Wrapper (keeps everything visible above overlay) -->
        <div class="content-wrapper">
            <!-- Welcome Section -->
            <div class="welcome-section border-bottom">
                <h1 class="h2">
                    Welcome
                    <span class="small">
                        <span class="text-black-50">{{ auth()->user()->name }}</span> in Student Admission System
                    </span>
                </h1>
            </div>

            <!-- Cards Section -->
            <div class="card-container">
                <!-- NTA Card -->
                <a href="{{ route('dashboard', 'NVA') }}" class="card nta-card text-decoration-none">
                    <i class="fa fa-university icon"></i>
                    <h3>VTA/VETA</h3>
                    <p class="status">Status:
                        <span class="badge bg-light text-dark">
                            {{ $veta->status ?? "Closed" }}
                        </span>
                    </p>
                </a>

                <!-- NVA Card -->
                <a href="{{ route('dashboard', 'NTA') }}" class="card nva-card text-decoration-none">
                    <i class="fa fa-graduation-cap icon"></i>
                    <h3>Diploma</h3>
                    <p class="status">Status:
                        <span class="badge bg-light text-dark">
                            {{ $diploma->status ?? "Closed" }}
                        </span>
                    </p>
                </a>
            </div>
        </div>
    </div>
@endsection