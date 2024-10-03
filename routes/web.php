<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpjController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\KotaController;
use App\Http\Controllers\Admin\PoolController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RuteController;
use App\Http\Controllers\Cso\TujuanController;
use App\Http\Controllers\Hrd\BiodataController;
use App\Http\Controllers\Admin\ArmadaController;
use App\Http\Controllers\Hrd\KaryawanController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Hrd\KondekturController;
use App\Http\Controllers\Hrd\PengemudiController;
use App\Http\Controllers\Admin\ProvinsiController;
use App\Http\Controllers\Cso\BookingController as CsoBookingController;
use App\Http\Controllers\Cso\BookingLaporanController;
use App\Http\Controllers\Cso\BookingsController;
use App\Http\Controllers\Cso\SeatBookingController;
use App\Http\Controllers\Cso\JadwalKondekturController;
use App\Http\Controllers\Cso\JadwalPengemudiController;
use App\Http\Controllers\Keuangan\PembayaranController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/main', function () {
        return view('main'); // Pastikan view 'main' ada
    })->name('main');

    Route::post('/book-seats', [SeatBookingController::class, 'bookSeats'])->name('bookseats');
});

Route::middleware(['auth', 'role:super-admin|admin|edp|Operasi'])->group(function () {
    
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/provinsi', ProvinsiController::class);
    Route::resource('/kota', KotaController::class);
    Route::resource('/pool', PoolController::class);
        // Route::put('/pool/{id}', [PoolController::class, 'update'])->name('pool.update');
    Route::resource('/jabatan', JabatanController::class);
    Route::resource('/rute', RuteController::class);
    Route::resource('/armada', ArmadaController::class);
    Route::resource('/biodata', BiodataController::class);
    Route::resource('/karyawan', KaryawanController::class);
    // Route::get('/biodata/{nik}', [BiodataController::class, 'show'])->name('biodata.show');
    Route::resource('/pengemudi', PengemudiController::class);
        // Route::get('/get-pool-name/{id}', [PengemudiController::class, 'getPoolName']);
    Route::resource('/kondektur', KondekturController::class);

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/create', [UserController::class, 'create'])->name('users/create');
    Route::post('/store', [UserController::class, 'store'])->name('users/store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('users/edit');
    // Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
});
Route::middleware(['auth', 'role:super-admin|admin|Cso|Operasi'])->group(function () {
    //Ini Bookings
    Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    // Route::post('/bookings/store', [BookingsController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/edit', [BookingsController::class, 'edit'])->name('bookings.edit');

    Route::get('/booking-laporan', [BookingLaporanController::class, 'bookingLaporan'])->name('bookingLaporan');
    Route::get('/booking-tgl-pdf', [BookingLaporanController::class, 'bookingtglPDF'])->name('cso.bookingtglPDF');

    Route::get('/export-bookings', [BookingsController::class, 'exportBookingsToExcel'])->name('export.bookings');


    //Ini Bookings Tufa
    Route::resource('booking', BookingController::class);
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('booking/store_detail', [BookingController::class, 'store_detail'])->name('booking.store_detail');
    Route::get('booking/detail/{id}', [BookingController::class, 'showDetail'])->name('booking.showDetail');

    Route::get('/booking', [BookingController::class, 'laporan'])->name('report/booking');

    Route::post('booking/update_pengemudi', [BookingController::class, 'update_pengemudi'])->name('booking.update_pengemudi');
    Route::get('booking/pengemudi/{id}', [BookingController::class, 'pengemudi'])->name('input/pengemudi');
    Route::post('booking/updateBusReservation', [BookingController::class, 'updateBusReservation'])->name('booking.updateBusReservation');
    Route::post('booking/updateDateReservation', [BookingController::class, 'updateDateReservation'])->name('booking.updateDateReservation');
    Route::get('booking/getTujuan', [BookingController::class, 'getTujuan'])->name('booking.getTujuan'); // Pastikan ini tidak duplikat
    Route::get('booking/getTotalHargaStd', [BookingController::class, 'getTotalHargaStd'])->name('booking.getTotalHargaStd');
    Route::get('booking/delete/bus/{id}', [BookingController::class, 'delete_bus'])->name('delete/bus');

    Route::post('/get-tujuan', [BookingController::class, 'getTujuan'])->name('getTujuan');
    Route::post('/get-total-harga-std', [BookingController::class, 'getTotalHargaStd'])->name('getTotalHargaStd');

});

Route::middleware(['auth', 'role:super-admin|admin|Operasi|Cso'])->group(function () {
    Route::resource('bookings', BookingsController::class);
    
    Route::get('/detail', [BookingController::class, 'report'])->name('report/detail');
    Route::get('/report', [BookingController::class, 'report'])->name('booking/report');
    Route::get('/laporan', [BookingController::class, 'laporan'])->name('booking/laporan');
    Route::get('/create', [BookingController::class, 'create'])->name('booking/create');
    Route::post('/store', [BookingController::class, 'store'])->name('booking/store');
    Route::get('/detail/{id}', [BookingController::class, 'detail'])->name('booking/detail');
    Route::post('/store-detail', [BookingController::class, 'store_detail'])->name('booking/store_detail');
    Route::get('/pengemudi/{id}', [BookingController::class, 'pengemudi'])->name('booking/pengemudi');
    Route::post('/update-data', [BookingController::class, 'update_pengemudi'])->name('booking/update');
    Route::get('/jadwal', [BookingController::class, 'jadwal'])->name('jadwal');
    Route::post('/getTujuan', [BookingController::class, 'getTujuan'])->name('getTujuan');
    Route::post('/getTotalHargaStd', [BookingController::class, 'getTotalHargaStd'])->name('getTotalHargaStd');
    Route::get('/edit/{id}', [BookingController::class, 'edit'])->name('booking/edit');
    Route::post('/update/{id}', [BookingController::class, 'update'])->name('bookings/update');
    Route::post('/update-reservation', [BookingController::class, 'updateBusReservation'])->name('booking/update');
    Route::post('/update-date', [BookingController::class, 'updateDateReservation'])->name('booking/update/date');
    Route::get('/excel', [BookingController::class, 'excel'])->name('booking/excel');
});
Route::group(['middleware' => ['auth', 'role:super admin|admin|owner|Keuangan|accounting']], function() {
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::get('/create/{id}', [PaymentController::class, 'create'])->name('payment/create');
    Route::post('/store', [PaymentController::class, 'store'])->name('payment/store');
    Route::get('payment/report', [PaymentController::class, 'report'])->name('payment/report');
    Route::get('payment/invoice/{id}', [PaymentController::class, 'invoice'])->name('payment/invoice');


    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/create', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/bookings/{id}', [BookingsController::class, 'show'])->name('bookings.show');


});

Route::get('/unauthorized', function () {
    return view('unauthorized'); // Pastikan view 'unauthorized' ada
})->name('unauthorized');

Route::get('booking/detail/{id}/{date}', 'BookingController@showDetail')->name('booking.showDetail');

// Route::prefix('booking')->group(function () {
//     Route::get('/', [BookingController::class, 'index'])->name('booking');
//     Route::get('/report', [BookingController::class, 'report'])->name('booking/report');
//     Route::get('/laporan', [BookingController::class, 'laporan'])->name('booking/laporan');
//     Route::get('/create', [BookingController::class, 'create'])->name('booking/create');
//     Route::post('/store', [BookingController::class, 'store'])->name('booking/store');
//     Route::get('/pengemudi/{id}', [BookingController::class, 'pengemudi'])->name('booking/pengemudi');
//     Route::post('/update-data', [BookingController::class, 'update_pengemudi'])->name('booking/update');
//     Route::get('/detail/{id}', [BookingController::class, 'detail'])->name('booking/detail');
//     Route::post('/store-detail', [BookingController::class, 'store_detail'])->name('booking/store_detail');
//     Route::get('/jadwal', [BookingController::class, 'jadwal'])->name('jadwal');
//     Route::post('/getTujuan', [BookingController::class, 'getTujuan'])->name('getTujuan');
//     Route::post('/getTotalHargaStd', [BookingController::class, 'getTotalHargaStd'])->name('getTotalHargaStd');
//     Route::get('/edit/{id}', [BookingController::class, 'edit'])->name('booking/edit');
//     Route::post('/update-reservation', [BookingController::class, 'updateBusReservation'])->name('booking/update');
//     Route::post('/update-date', [BookingController::class, 'updateDateReservation'])->name('booking/update/date');
// });

// Route::prefix('report')->group(function () {
//     Route::get('/booking', [BookingController::class, 'laporan'])->name('report/booking');
//     Route::get('/detail', [BookingController::class, 'report'])->name('report/detail');
//     Route::get('/spj', [SpjController::class, 'report'])->name('report/spj');
// });

// Route::prefix('payment')->group(function () {

//     Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
//     Route::get('/create/{id}', [PaymentController::class, 'create'])->name('payment/create');
//     Route::post('/store', [PaymentController::class, 'store'])->name('payment/store');
// });

Route::prefix('spj')->group(function () {

    Route::get('/', [SpjController::class, 'index'])->name('spj');
    Route::get('/detail/{id}', [SpjController::class, 'detail'])->name('spj/detail');
    Route::get('/data/{id}', [SpjController::class, 'data'])->name('spj/data');
    Route::post('/keluar/{id}', [SpjController::class, 'keluar'])->name('spj/keluar');
    // Route::post('/masuk/{id}', [SpjController::class, 'masuk'])->name('spj/masuk');
    Route::get('/print/out/{id}', [SpjController::class, 'detail_out'])->name('spj/print_out');
    Route::post('/print/out/save/{id}', [SpjController::class, 'save_detail_out'])->name('spj/save_detail_out');
    Route::get('/back/{id}', [SpjController::class, 'back'])->name('spj/back');
    Route::get('/print_out/{id}', [SpjController::class, 'print'])->name('spj/print');
    Route::get('/print/in/{id}', [SpjController::class, 'detail_in'])->name('spj/print_in');
    Route::post('/print/in/save/{id}', [SpjController::class, 'save_detail_in'])->name('spj/save_detail_in');
    Route::post('/print/out/store', [SpjController::class, 'store_print_out'])->name('spj/print_out/store');
    Route::post('/print/in/store', [SpjController::class, 'store_print_in'])->name('spj/print_in/store');
    Route::post('/biaya_lain/store', [SpjController::class, 'biaya_lain'])->name('spj/biaya_lain');
});


Route::prefix('schedule')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('schedule');
    //Bus
    Route::get('/bus-schedule', [ScheduleController::class, 'showBusSchedule'])->name('schedule.show');
    Route::get('/jadwalbus-pdf', [ScheduleController::class, 'jadwalbusToPdf'])->name('jadwalbus.pdf');
    //Pengemudi
    Route::get('/pengemudi-schedule', [JadwalPengemudiController::class, 'showJadwalPengemudi'])->name('pengemudi.show');
    //Kondektur
    Route::get('/kondektur-schedule', [JadwalKondekturController::class, 'showJadwalKondektur'])->name('kondektur.show');
});

Route::resource('/tujuan', TujuanController::class);
