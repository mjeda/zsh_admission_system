<?php

use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ControlNumberController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseCriteriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\NacteApiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Portal\HomeController as PortalHomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SelectedCourseController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\UserController;
use App\Http\Livewire\Veta\FormComponent;
use App\Http\Livewire\Veta\RegisterComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();


Route::get('/', [HomeController::class, 'index']);
Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('veta/register', RegisterComponent::class)->name('veta.register');

Route::middleware('auth')->group(function(){
    Route::get("user/change-password/", [UserController::class, "getChangePassword"])->name("user.change.password");
    Route::post("user/update-password/", [UserController::class, "updatePassword"])->name("user.update.password");
});

Route::middleware(['isAdmin', 'auth'])->group( function (){
// Route::get('{type}/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
// Admission
Route::resource("admission", AdmissionController::class)->except("destroy", "update")->middleware('permission:view admission');
Route::get('admission/update/{admission}', [AdmissionController::class, 'update'])->name('admission.update')->middleware('permission:edit admission');
Route::post('admission/change/{selected_course}', [AdmissionController::class, 'changeCourse'])->name('admission.course.update')->middleware('permission:change admission course');
Route::post('admission/update-information/{admission}', [AdmissionController::class, 'updatedInfo'])->name('admission.update.info');
Route::get('admission/reset-password/{user}', [AdmissionController::class, 'resetPassword'])->name('admission.reset.password');

// Payments
Route::get('payments', [PaymentController::class, 'index'])->name('payments')->middleware('permission:view payment and control number');

// Control number
Route::get('control-numbers', [ControlNumberController::class, 'index'])->name('control-numbers')->middleware('permission:view payment and control number');
Route::get('control-numbers/{control_number}/extend', [ControlNumberController::class, 'extendExpireDate'])->name('control-number.extend-time')->middleware('permission:view payment and control number');

// Selected Student
Route::get('selections', [SelectedCourseController::class, 'index'])->name('selections')->middleware('permission:view selection');
Route::post('selections/create', [SelectedCourseController::class, 'create'])->name('selections.collect')->middleware('permission:collect selected');
Route::get('selections/show/{course}/{intake}', [SelectedCourseController::class, 'show'])->name('selections.collection')->middleware('permission:view collection');
Route::get('selections/delete/{item}', [SelectedCourseController::class, 'destroy'])->name('selections.delete')->middleware('permission:remove collection selected');
Route::get('selections/submission/{course}/{intake}', [SelectedCourseController::class, 'store'])->name('selections.submission')->middleware('permission:submit to NACTE');
Route::get('selections/submission_index/{course}/{intake}', [SelectedCourseController::class, 'store_index'])->name('selections.submission_index')->middleware('permission:view submission');
Route::get('selections/submission_approval_index/{course}/{intake}', [SelectedCourseController::class, 'submission_status'])->name('selections.submission_approval_index')->middleware('permission:view status');

// Users
Route::resource("user", UserController::class)->except("destroy, show")->middleware('permission:view user');
Route::get("user/reset-password/{user}", [UserController::class, "resetPassword"])->name("user.reset.password");
Route::get("user/delete/{user}", [UserController::class, "destroy"])->name("user.delete")->middleware('permission:delete user');

// Permission
Route::get('user/permission/{user}', [UserController::class, 'showPermission'])->name('show.permission')->middleware('permission:view permission');
Route::post('user/permission/{user}', [UserController::class, 'storePermission'])->name('store.permission')->middleware('permission:add permission');

// Courses
Route::get("courses/{course?}", [CourseController::class, "index"])->name("course.index")->middleware('permission:view course');
Route::post("course/store", [CourseController::class, "store"])->name("course.store")->middleware('permission:add course');
Route::get("course/status/{course}", [CourseController::class, "status"])->name("course.status")->middleware('permission:change course status');
Route::post("course/update/{course}", [CourseController::class, "update"])->name("course.update")->middleware('permission:edit course');
Route::get("course/delete/{course}", [CourseController::class, "destroy"])->name("course.delete")->middleware('permission:delete course');

// Course Criteria
Route::get("course/criteria/{course}/{courseCriteria?}", [CourseCriteriaController::class, "index"])->name("criteria.index")->middleware('permission:view criteria');
Route::post("criteria/{course}/store", [CourseCriteriaController::class, "store"])->name("criteria.store")->middleware('permission:add criteria');
Route::post("criteria/update/{courseCriteria}", [CourseCriteriaController::class, "update"])->name("criteria.update")->middleware('permission:edit criteria');
Route::get("criteria/delete/{courseCriteria}", [CourseCriteriaController::class, "destroy"])->name("criteria.delete")->middleware('permission:delete criteria');

// Courses
Route::get("intakes/{intake?}", [IntakeController::class, "index"])->name("intake.index")->middleware('permission:view intake');
Route::post("intake/store", [IntakeController::class, "store"])->name("intake.store")->middleware('permission:add intake');
Route::post("intake/update/{intake}", [IntakeController::class, "update"])->name("intake.update")->middleware('permission:edit intake');
Route::get("intake/delete/{intake}", [IntakeController::class, "destroy"])->name("intake.delete")->middleware('permission:delete intake');
Route::get("intake/status/{intake}", [IntakeController::class, "status"])->name("intake.status")->middleware('permission:change intake status');
Route::get('/cash-payments', [PaymentController::class, 'transact'])->name('cash.payment')->middleware('permission:pay control number');

// Reports
Route::get('payment-report', [ReportController::class, 'payment'])->name('report.payment')->middleware('permission:view payment report');
Route::get('admission-report', [ReportController::class, 'admission'])->name('report.admission')->middleware('permission:view admission report');
Route::get('admission-by-course-report', [ReportController::class, 'admissionByCourse'])->name('report.admission_by_course')->middleware('permission:view admission report');
Route::get('selection-by-course-report', [ReportController::class, 'selectionByCourse'])->name('report.selection_by_course')->middleware('permission:view admission report');
Route::get('admission-by-results-report', [ReportController::class, 'admissionByResults'])->name('report.search_results')->middleware('permission:search result');

//send sms
Route::get('/send-sms', [SmsController::class, 'index'])->name('send.sms.form')->middleware('permission:send sms');

// Nacte
Route::prefix("nacte")->group(function (){
    Route::get('/pushed/list', [NacteApiController::class, 'getPushedList'])->name('nacte.pushed.list');
    Route::get('/verified/student', [NacteApiController::class, 'getVerifiedStudent'])->name('nacte.verified.student');
    Route::get('/enrol/student/{verification_id}/{programme}/{intake}/{year}', [NacteApiController::class, 'enrolStudent'])->name('nacte.enrol.student');
    Route::get('/enrol/all/student/{programme}/{intake}', [NacteApiController::class, 'enrolAllStudent'])->name('nacte.enrol.all.student');

    Route::get('/feedback-error', [NacteApiController::class, 'feedbackError'])->name('nacte.feedback-error');
    Route::get('/fix-error/{data}', [NacteApiController::class, 'fixError'])->name('nacte.fix-error');
    Route::post('/feedback-error/{data}', [NacteApiController::class, 'uploadFeedbackError'])->name('nacte.upload-feedback-error');
});

});


// Application Route
Route::prefix("portal")->middleware("auth")->group(function (){
    Route::get('/', [PortalHomeController::class, 'index'])->name('portal-dashboard');
    Route::get('/application', [PortalHomeController::class, 'application'])->name('portal-application');
    Route::get('/admission', [AppController::class, 'admissionForm'])->name('portal-admission');
    Route::get('/admission-detail/{admission}', [AppController::class, 'show'])->name('portal-admission-detail');
});

Route::prefix("veta")->middleware("auth")->group(function (){
    Route::get('/', FormComponent::class)->name('veta.form');
});

Route::get('/test', function(){
   $test = new \App\Action\submitNECTA();

   $test->handle();
});

Route::get('/error-page', function () {
    $error = session('error');
    return view('error-page', compact('error'));
})->name('error.page');



