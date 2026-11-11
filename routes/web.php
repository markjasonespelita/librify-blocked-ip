<?php

use App\Http\Controllers\FacebookMessengerWebhookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Events\SamplePrivateEvent;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SmarkController;
use App\Http\Controllers\PaymongoPaymentController;
use App\Http\Controllers\SpatieRolesController;
// end of import

use App\Http\Controllers\UsersController;
use App\Models\Users;

// end of import

use App\Http\Controllers\MunicipalitiesController;
use App\Models\Municipalities;

// end of import

use App\Http\Controllers\OltsController;
use App\Models\Olts;

// end of import

use App\Http\Controllers\StatusesController;
use App\Models\Statuses;

// end of import

use App\Http\Controllers\SitesController;
use App\Models\Sites;

// end of import

use App\Http\Controllers\StatusreportsController;
use App\Models\Statusreports;

// end of import






Route::get('/', function () {
    return view('welcome');
});

// Facebook Messenger Webhook

Route::get('/webhook', [FacebookMessengerWebhookController::class, 'verify']);
Route::post('/webhook', [FacebookMessengerWebhookController::class, 'handle']);

// PAYMONGO CALLBACK

Route::post('/callback-payment', [PaymongoPaymentController::class, 'callbackPayment']);

Route::middleware([
    'auth:sanctum',
    'web',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // release notes

    Route::get('/release-notes', function () {
        return view('release-notes');
    });

    // spatie

    Route::get('/spatie-roles', [SpatieRolesController::class, 'index'])->name('roles.spatie-roles');
    Route::post('/store-spatie-role', [SpatieRolesController::class, 'storeSpatieRole']);               // create role
    Route::post('/update-spatie-role/{roleId}', [SpatieRolesController::class, 'updateSpatieRole']);               // create role
    Route::get('/destroy-spatie-role/{roleId}', [SpatieRolesController::class, 'destroySpatieRole']);               // create role
    Route::post('/update-permission-for-spatie-role/{roleId}', [SpatieRolesController::class, 'updatePermissionForSpatieRole']);

    // firebase

    Route::post('/save-fcm-token', function (Request $request)
    {
        auth()->user()->update([
            'fcm_token' => $request->token,
        ]);

        return ['status' => 'saved'];
    });

    Route::get('/user', function () {
        return response()->json(Auth::user());
    });

    // WEBSOCKETS

    Route::get('/test-pusher', function () {
        event(new SamplePrivateEvent(23, 'Hello from Laravel!'));
        return 'Event has been sent!';
    });

    // END WEB SOCKETS

    // PAYMONGO PAYMENT

    Route::get('/test-payment', [PaymongoPaymentController::class, 'testPaymentPage']);
    Route::post('/create-payment', [PaymongoPaymentController::class, 'createPayment']);

    // END PAYMONGO PAYMENT

    // CHAT ROUTES

    Route::get('/initialize-chat/{usersId}/{recieversId}', [SmarkController::class, 'initializeChat']);
    Route::post('/send-chat', [SmarkController::class, 'sendChat']);
    Route::get('/fetch-messages/{chatsId}', [SmarkController::class, 'fetchMessages']);
    Route::get('/chat-history', [SmarkController::class, 'chatHistory']);
    Route::post('/upload-files', [SmarkController::class, 'uploadFiles']);
    Route::post('/delete-chat', [SmarkController::class, 'deleteChat']);
    Route::post('/unsent-message', [SmarkController::class, 'unsentMessage']);
    Route::post('/chat-files', [SmarkController::class, 'chatFiles']);

    // END CHAT ROUTES

    Route::get('/activity-logs', [SmarkController::class, 'activityLogs']);

    // backup

    Route::get('/backups', [SmarkController::class, 'backups']);
    Route::get('/backup-process', [SmarkController::class, 'backupProcess']);

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // MODEL VIEWER

    Route::get('/_database/{model?}', [SmarkController::class, 'modelViewer'])->name('models.index');

    // POSTMAN

    Route::get('/_postman', [SmarkController::class, 'postman']);

    // end...

    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/create-users', [UsersController::class, 'create'])->name('users.create');
    Route::get('/edit-users/{usersId}', [UsersController::class, 'edit'])->name('users.edit');
    Route::get('/show-users/{usersId}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/delete-users/{usersId}', [UsersController::class, 'delete'])->name('users.delete');
    Route::get('/destroy-users/{usersId}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::post('/store-users', [UsersController::class, 'store'])->name('users.store');
    Route::post('/update-users/{usersId}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/users-delete-all-bulk-data', [UsersController::class, 'bulkDelete']);
    Route::post('/users-move-to-trash-all-bulk-data', [UsersController::class, 'bulkMoveToTrash']);
    Route::post('/users-restore-all-bulk-data', [UsersController::class, 'bulkRestore']);
    Route::get('/trash-users', [UsersController::class, 'trash']);
    Route::get('/restore-users/{usersId}', [UsersController::class, 'restore'])->name('users.restore');

    // Users Search
    Route::get('/users-search', [SmarkController::class, 'usersSearch']);

    // Users Paginate
    Route::get('/users-paginate', [SmarkController::class. 'usersPaginate']);

    // Users Filter
    Route::get('/users-filter', [SmarkController::class, 'userFilter']);

    // Messenger INIT page

    Route::get('/receive-chat-notification', [FacebookMessengerWebhookController::class, 'init']);

    // end...

    Route::get('/municipalities', [MunicipalitiesController::class, 'index'])->name('municipalities.index');
    Route::get('/create-municipalities', [MunicipalitiesController::class, 'create'])->name('municipalities.create');
    Route::get('/edit-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'edit'])->name('municipalities.edit');
    Route::get('/show-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'show'])->name('municipalities.show');
    Route::get('/delete-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'delete'])->name('municipalities.delete');
    Route::get('/destroy-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'destroy'])->name('municipalities.destroy');
    Route::post('/store-municipalities', [MunicipalitiesController::class, 'store'])->name('municipalities.store');
    Route::post('/update-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'update'])->name('municipalities.update');
    Route::post('/municipalities-delete-all-bulk-data', [MunicipalitiesController::class, 'bulkDelete']);
    Route::post('/municipalities-move-to-trash-all-bulk-data', [MunicipalitiesController::class, 'bulkMoveToTrash']);
    Route::post('/municipalities-restore-all-bulk-data', [MunicipalitiesController::class, 'bulkRestore']);
    Route::get('/trash-municipalities', [MunicipalitiesController::class, 'trash']);
    Route::get('/restore-municipalities/{municipalitiesId}', [MunicipalitiesController::class, 'restore'])->name('municipalities.restore');

    // Municipalities Search
    Route::get('/municipalities-search', [MunicipalitiesController::class, 'search']);

    // Municipalities Paginate
    Route::get('/municipalities-paginate', [MunicipalitiesController::class, 'paginate']);

    // Municipalities Filter
    Route::get('/municipalities-filter', [MunicipalitiesController::class, 'filter']);

    // end...

    Route::get('/olts', [OltsController::class, 'index'])->name('olts.index');
    Route::get('/create-olts', [OltsController::class, 'create'])->name('olts.create');
    Route::get('/edit-olts/{oltsId}', [OltsController::class, 'edit'])->name('olts.edit');
    Route::get('/show-olts/{oltsId}', [OltsController::class, 'show'])->name('olts.show');
    Route::get('/delete-olts/{oltsId}', [OltsController::class, 'delete'])->name('olts.delete');
    Route::get('/destroy-olts/{oltsId}', [OltsController::class, 'destroy'])->name('olts.destroy');
    Route::post('/store-olts', [OltsController::class, 'store'])->name('olts.store');
    Route::post('/update-olts/{oltsId}', [OltsController::class, 'update'])->name('olts.update');
    Route::post('/olts-delete-all-bulk-data', [OltsController::class, 'bulkDelete']);
    Route::post('/olts-move-to-trash-all-bulk-data', [OltsController::class, 'bulkMoveToTrash']);
    Route::post('/olts-restore-all-bulk-data', [OltsController::class, 'bulkRestore']);
    Route::get('/trash-olts', [OltsController::class, 'trash']);
    Route::get('/restore-olts/{oltsId}', [OltsController::class, 'restore'])->name('olts.restore');

    // Olts Search
    Route::get('/olts-search', [OltsController::class, 'search']);

    // Olts Paginate
    Route::get('/olts-paginate', [OltsController::class, 'paginate']);

    // Olts Filter
    Route::get('/olts-filter', [OltsController::class, 'filter']);

    // end...

    Route::get('/statuses', [StatusesController::class, 'index'])->name('statuses.index');
    Route::get('/create-statuses', [StatusesController::class, 'create'])->name('statuses.create');
    Route::get('/edit-statuses/{statusesId}', [StatusesController::class, 'edit'])->name('statuses.edit');
    Route::get('/show-statuses/{statusesId}', [StatusesController::class, 'show'])->name('statuses.show');
    Route::get('/delete-statuses/{statusesId}', [StatusesController::class, 'delete'])->name('statuses.delete');
    Route::get('/destroy-statuses/{statusesId}', [StatusesController::class, 'destroy'])->name('statuses.destroy');
    Route::post('/store-statuses', [StatusesController::class, 'store'])->name('statuses.store');
    Route::post('/update-statuses/{statusesId}', [StatusesController::class, 'update'])->name('statuses.update');
    Route::post('/statuses-delete-all-bulk-data', [StatusesController::class, 'bulkDelete']);
    Route::post('/statuses-move-to-trash-all-bulk-data', [StatusesController::class, 'bulkMoveToTrash']);
    Route::post('/statuses-restore-all-bulk-data', [StatusesController::class, 'bulkRestore']);
    Route::get('/trash-statuses', [StatusesController::class, 'trash']);
    Route::get('/restore-statuses/{statusesId}', [StatusesController::class, 'restore'])->name('statuses.restore');

    // Statuses Search
    Route::get('/statuses-search', [StatusesController::class, 'search']);

    // Statuses Paginate
    Route::get('/statuses-paginate', [StatusesController::class, 'paginate']);

    // Statuses Filter
    Route::get('/statuses-filter', [StatusesController::class, 'filter']);

    // end...

    Route::get('/sites', [SitesController::class, 'index'])->name('sites.index');
    Route::get('/create-sites', [SitesController::class, 'create'])->name('sites.create');
    Route::get('/edit-sites/{sitesId}', [SitesController::class, 'edit'])->name('sites.edit');
    Route::get('/show-sites/{sitesId}', [SitesController::class, 'show'])->name('sites.show');
    Route::get('/delete-sites/{sitesId}', [SitesController::class, 'delete'])->name('sites.delete');
    Route::get('/destroy-sites/{sitesId}', [SitesController::class, 'destroy'])->name('sites.destroy');
    Route::post('/store-sites', [SitesController::class, 'store'])->name('sites.store');
    Route::post('/update-sites/{sitesId}', [SitesController::class, 'update'])->name('sites.update');
    Route::post('/sites-delete-all-bulk-data', [SitesController::class, 'bulkDelete']);
    Route::post('/sites-move-to-trash-all-bulk-data', [SitesController::class, 'bulkMoveToTrash']);
    Route::post('/sites-restore-all-bulk-data', [SitesController::class, 'bulkRestore']);
    Route::get('/trash-sites', [SitesController::class, 'trash']);
    Route::get('/restore-sites/{sitesId}', [SitesController::class, 'restore'])->name('sites.restore');

    // Sites Search
    Route::get('/sites-search', [SitesController::class, 'search']);

    // Sites Paginate
    Route::get('/sites-paginate', [SitesController::class, 'paginate']);

    // Sites Filter
    Route::get('/sites-filter', [SitesController::class, 'filter']);

    // end...

    Route::get('/statusreports', [StatusreportsController::class, 'index'])->name('statusreports.index');
    Route::get('/create-statusreports', [StatusreportsController::class, 'create'])->name('statusreports.create');
    Route::get('/edit-statusreports/{statusreportsId}', [StatusreportsController::class, 'edit'])->name('statusreports.edit');
    Route::get('/show-statusreports/{statusreportsId}', [StatusreportsController::class, 'show'])->name('statusreports.show');
    Route::get('/delete-statusreports/{statusreportsId}', [StatusreportsController::class, 'delete'])->name('statusreports.delete');
    Route::get('/destroy-statusreports/{statusreportsId}', [StatusreportsController::class, 'destroy'])->name('statusreports.destroy');
    Route::post('/store-statusreports', [StatusreportsController::class, 'store'])->name('statusreports.store');
    Route::post('/update-statusreports/{statusreportsId}', [StatusreportsController::class, 'update'])->name('statusreports.update');
    Route::post('/statusreports-delete-all-bulk-data', [StatusreportsController::class, 'bulkDelete']);
    Route::post('/statusreports-move-to-trash-all-bulk-data', [StatusreportsController::class, 'bulkMoveToTrash']);
    Route::post('/statusreports-restore-all-bulk-data', [StatusreportsController::class, 'bulkRestore']);
    Route::get('/trash-statusreports', [StatusreportsController::class, 'trash']);
    Route::get('/restore-statusreports/{statusreportsId}', [StatusreportsController::class, 'restore'])->name('statusreports.restore');

    // Statusreports Search
    Route::get('/statusreports-search', [StatusreportsController::class, 'search']);

    // Statusreports Paginate
    Route::get('/statusreports-paginate', [StatusreportsController::class, 'paginate']);

    // Statusreports Filter
    Route::get('/statusreports-filter', [StatusreportsController::class, 'filter']);

    // end...

});
