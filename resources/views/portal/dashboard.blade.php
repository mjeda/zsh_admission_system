@extends('layouts.portal')

@section('content')
    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-1">Welcome, <strong class="text-capitalize">{{ $user->first_name .' '. $user->middle_name .' '. $user->last_name }}</strong></h5>
                <p class="text-muted mb-0">This is your admission dashboard.</p>
            </div>
            <div>
                <a href="{{ route('portal-admission') }}" class="btn btn-outline-danger">+ New Application</a>
            </div>
        </div>
    </div>

    <!-- Progress Tracker -->
    <div class="card shadow-sm my-4">
        <div class="card-body">
            <h6 class="mb-3">Application Progress</h6>
            <div class="progress mb-2" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $progress }}%;">
                    Step {{ $user->step }} of 4
                </div>
            </div>
            <p class="small text-muted mb-0">
                {{ $message }}
            </p>
        </div>
    </div>

    <!-- Application tips -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">📌 Maelekezo ya Kujaza Fomu ya Maombi</h5>

            <div class="row g-3">
                <!-- Step 1 -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold">1. Taarifa Binafsi</h6>
                        <p class="mb-0 text-muted">
                            Tafadhali jaza taarifa zako binafsi zote zinazohitajika (jina kamili, tarehe ya kuzaliwa, jinsia, utaifa, mawasiliano).
                            Hakikisha taarifa zote ni sahihi kabla ya kuendelea.
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold">2. Taarifa za Mtu wa Karibu</h6>
                        <p class="mb-0 text-muted">
                            Weka taarifa sahihi za mzazi, mlezi au ndugu wa karibu. Taarifa hizi ni muhimu kwa mawasiliano ya dharura na rasmi.
                        </p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold">3. Malipo</h6>
                        <p class="mb-0 text-muted">
                            Fanya malipo ya Tsh <strong>30,000/=</strong> kupitia <strong>Control Number</strong> utakayopatiwa.
                            <strong>control number itaisha baada ya wiki 1</strong>. Hakikisha unahifadhi uthibitisho wa malipo kwa kumbukumbu.
                        </p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light">
                        <h6 class="fw-bold">4. Uchaguzi wa Kozi</h6>
                        <p class="mb-0 text-muted">
                            Chagua <strong>kozi tatu</strong> kwa mpangilio wa kipaumbele (chaguo la kwanza hadi la tatu).
                            Baada ya kumaliza uchaguzi, kagua taarifa zako zote na <strong>wasilisha</strong> maombi yako.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Final Note -->
            <div class="alert alert-info mt-4 mb-0">
                Baada ya kuwasilisha, utapokea ujumbe wa kuthibitisha. Tafadhali subiri matokeo ya udahili yatakapotangazwa.
                Endapo utakutana na changamoto yoyote, wasiliana na ofisi ya udahili kwa msaada.
            </div>
        </div>
    </div>

@endsection
