<?php

use App\Http\Controllers\ADMS\AttendancesController;
use App\Http\Controllers\ADMS\AttendanceSummaryController;
use App\Http\Controllers\ADMS\FpDevicesController;
use App\Http\Controllers\ADMS\IclockController;
use App\Http\Controllers\ADMS\WorkTimeController;
use App\Http\Controllers\Inventory\GoodsController;
use App\Http\Controllers\Inventory\UnitTypesController;
use App\Http\Controllers\Inventory\UsedItemsController;
use App\Http\Controllers\JournalAdjustment\InitialJournalController;
use App\Http\Controllers\JournalAdjustment\JournalAdjustmentController;
use App\Http\Controllers\Journals\GeneralJournalController;
use App\Http\Controllers\Journals\GeneralLedgerController;
use App\Http\Controllers\ManageUser\EducationCertificateController;
use App\Http\Controllers\ManageUser\EducationController;
use App\Http\Controllers\ManageUser\FamilyInformationController;
use App\Http\Controllers\ManageUser\HealthInformationController;
use App\Http\Controllers\ManageUser\IdentityInformationController;
use App\Http\Controllers\ManageUser\JobExperiencesController;
use App\Http\Controllers\ManageUser\JobInformationController;
use App\Http\Controllers\ManageUser\ManageUserLeavesController;
use App\Http\Controllers\ManageUser\PayrollController;
use App\Http\Controllers\ManageUser\PermissionController;
use App\Http\Controllers\ManageUser\UserController;
use App\Http\Controllers\Master\AccountController;
use App\Http\Controllers\Master\AccountTransactionsController;
use App\Http\Controllers\Master\BranchesController;
use App\Http\Controllers\Master\ContactController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\ProductController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\ServicesCategoryController;
use App\Http\Controllers\Master\SubAccountController;
use App\Http\Controllers\Operational\SPController;
use App\Http\Controllers\Setting\MenuController;
use App\Http\Controllers\Transaction\BastController;
use App\Http\Controllers\Transaction\ExpenditureController;
use App\Http\Controllers\Transaction\FabController;
use App\Http\Controllers\Transaction\InvoiceController;
use App\Http\Controllers\Transaction\OfferingLettersController;
use App\Http\Controllers\UserProfile\LeaveAndPermissionController;
use App\Http\Controllers\Utilities\CompanyProfileController;
use App\Http\Controllers\Utilities\LetterHeadController;
use App\Http\Controllers\Utilities\NotificationsController;
use App\Http\Controllers\Utilities\UserProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::get('/', function () {
    return view('auth.login');
});

//Route::get('/', static function () {
//    return redirect('/login');
//});

Auth::routes();

Route::group(['middleware' => ['auth']], static function () {
    //dashboard
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('/manage-users')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/data', [UserController::class, 'usersData']);
            Route::get('/roles/data', [UserController::class, 'rolesData']);
            Route::get('/branch/data', [UserController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [UserController::class, 'filterByBranch']);
            Route::get('/search', [UserController::class, 'search']);
            Route::get('/placement/data', [UserController::class, 'getPlacementData']);
            Route::get('/placement/selected/{user}', [UserController::class, 'getSelectedPlacement']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/edit/{user}', [UserController::class, 'edit']);
            Route::get('/get-selected-branch/{user}', [UserController::class, 'getSelectedBranch']);
            Route::get('/show/{user}', [UserController::class, 'show']);
            Route::get('/detail/{user}', [UserController::class, 'detail']);
            Route::get('/department/data', [UserController::class, 'getDepartmentData']);
            Route::get('/absent/data/{user}', [UserController::class, 'getAbsentData']);
            Route::post('/update/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
            Route::post('/import', [UserController::class, 'import']);
        });


        Route::prefix('identity-information')->group(function () {
            Route::get('/{user}', [IdentityInformationController::class, 'index']);
            Route::post('/{user}', [IdentityInformationController::class, 'update']);
        });

        Route::prefix('job-information')->group(function () {
            Route::get('/{user}', [JobInformationController::class, 'index']);
            Route::post('/{user}', [JobInformationController::class, 'update']);
            Route::get('/view-file/{user}', [JobInformationController::class, 'viewFile']);
            Route::get('/department/selected/{user}', [JobInformationController::class, 'getSelectedDepartment']);
        });

        Route::prefix('educations')->group(function () {
            Route::get('/{user}', [EducationController::class, 'getRelatedUserEducation']);
            Route::post('/{user}', [EducationController::class, 'update']);
            Route::get('/view-file/{user}', [EducationController::class, 'viewFile']);
        });

        Route::prefix('education-certificates')->group(function () {
            Route::get('/{user}', [EducationCertificateController::class, 'getEducationCertificate']);
            Route::post('/store/{user}', [EducationCertificateController::class, 'store']);
            Route::get('/edit/{educationCertificate}', [EducationCertificateController::class, 'edit']);
            Route::post('/update/{educationCertificate}', [EducationCertificateController::class, 'update']);
            Route::delete('destroy/{educationCertificate}', [EducationCertificateController::class, 'destroy']);
            Route::get('/view-file/{educationCertificate}', [EducationCertificateController::class, 'viewFile']);
        });


        Route::prefix('job-experiences')->group(function () {
            Route::get('/{user}', [JobExperiencesController::class, 'getRelatedUserJobExperience']);
            Route::post('/store/{user}', [JobExperiencesController::class, 'store']);
            Route::get('/edit/{jobExperience}', [JobExperiencesController::class, 'edit']);
            Route::post('/update/{jobExperience}', [JobExperiencesController::class, 'update']);
            Route::delete('/destroy/{jobExperience}', [JobExperiencesController::class, 'destroy']);
        });


        Route::prefix('family-informations')->group(function () {
            Route::get('/{user}', [FamilyInformationController::class, 'getRelatedFamilyInformation']);
            Route::post('/{user}', [FamilyInformationController::class, 'updateOrCreate']);
        });
        Route::prefix('health-informations')->group(function () {
            Route::get('/{user}', [HealthInformationController::class, 'getRelatedUserHealthInformation']);
            Route::post('/{user}', [HealthInformationController::class, 'updateOrCreate']);
        });


        Route::prefix('/permissions')->group(function () {
            Route::get('/', [PermissionController::class, 'index']);
            Route::get('/data', [PermissionController::class, 'permissionData']);
            Route::get('/search', [PermissionController::class, 'search']);
            Route::post('/', [PermissionController::class, 'store']);
            Route::get('/show/{permission}', [PermissionController::class, 'show']);
            Route::post('/update/{permission}', [PermissionController::class, 'update']);
            Route::delete('/{permission}', [PermissionController::class, 'destroy']);
        });


        Route::prefix('payroll')->group(function () {
            Route::get('/', [PayrollController::class, 'index']);
            Route::get('/data', [PayrollController::class, 'data']);
            Route::get('/user/data', [PayrollController::class, 'getUserData']);
            Route::get('/search', [PayrollController::class, 'search']);
            Route::get('/create', [PayrollController::class, 'create']);
            Route::post('/', [PayrollController::class, 'store']);
            Route::get('/{payroll}', [PayrollController::class, 'edit']);
            Route::post('/{payroll}', [PayrollController::class, 'update']);
            Route::delete('/{payroll}', [PayrollController::class, 'destroy']);
            Route::get('/export-pdf/{payroll}', [PayrollController::class, 'exportToPDF']);
        });
        Route::prefix('leaves')->group(function () {
            Route::get('/', [ManageUserLeavesController::class, 'index']);
            Route::get('/data', [ManageUserLeavesController::class, 'data']);
            Route::get('/search', [ManageUserLeavesController::class, 'search']);
            Route::get('/{leaveAndPermission}', [ManageUserLeavesController::class, 'detail']);
            Route::post('/{leaveAndPermission}', [ManageUserLeavesController::class, 'changeStatus']);
        });

        Route::prefix('sp')->group(function () {
            Route::get('/', [SPController::class, 'index']);
            Route::get('/data', [SpController::class, 'data']);
            Route::get('/search', [SpController::class, 'search']);
            Route::get('/users/data', [SpController::class, 'getUserData']);
            Route::get('/create', [SpController::class, 'create']);
            Route::post('/', [SpController::class, 'store']);
            Route::get('/{sp}', [SpController::class, 'edit']);
            Route::post('/{sp}', [SpController::class, 'update']);
            Route::get('/confirm/{sp}', [SpController::class, 'confirm']);
            Route::delete('/{sp}', [SpController::class, 'destroy']);
            Route::get('export-pdf/{sp}', [SPController::class, 'exportToPDF']);
        });
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::get('/detail', [NotificationsController::class, 'detail']);
        Route::post('/mark-as-read', [NotificationsController::class, 'markAsRead']);
    });

    // account
    Route::prefix('/account-master')->group(function () {
        Route::prefix('/account')->group(function () {
            Route::get('/', [AccountController::class, 'index']);
            Route::get('/data', [AccountController::class, 'data']);
            Route::get('/category/data', [AccountController::class, 'categoriesData']);
            Route::get('/branch/data', [AccountController::class, 'branchData']);
            Route::get('/filter/branch/{branch}', [AccountController::class, 'filter']);
            Route::get('/branch/selected/{account}', [AccountController::class, 'getSelectedBranch']);
            Route::get('/getSelectedKategori/{id}', [AccountController::class, 'getSelectedCategory']);
            Route::get('/search', [AccountController::class, 'search']);
            Route::post('/', [AccountController::class, 'store']);
            Route::post('/import', [AccountController::class, 'import']);
            Route::get('/edit/{account}', [AccountController::class, 'edit']);
            Route::post('/update/{account}', [AccountController::class, 'update']);
            Route::delete('/{account}', [AccountController::class, 'destroy']);
        });

        // subaccount
        Route::prefix('sub-account')->group(function () {
            Route::get('/', [SubAccountController::class, 'index']);
            Route::get('/data', [SubAccountController::class, 'data']);
            Route::get('account/data', [SubAccountController::class, 'accountData']);
            Route::get('/search', [SubAccountController::class, 'search']);
            Route::post('/', [SubAccountController::class, 'store']);
            Route::get('/account/selected/{subAccount}', [SubAccountController::class, 'selectedAccount']);
            Route::get('/edit/{subAccount}', [SubAccountController::class, 'edit']);
            Route::post('/update/{subAccount}', [SubAccountController::class, 'update']);
            Route::delete('/{subAccount}', [SubAccountController::class, 'destroy']);
            Route::post('/import/', [SubAccountController::class, 'import']);
        });

        Route::prefix('account-transaction')->group(function () {
            Route::get('/', [AccountTransactionsController::class, 'index']);
            Route::get('/data', [AccountTransactionsController::class, 'data']);
            Route::get('/search', [AccountTransactionsController::class, 'search']);
            Route::get('detail/{account}', [AccountTransactionsController::class, 'detail']);
        });
    });

    // branch
    Route::prefix('master')->group(function () {
        Route::prefix('branch/')->group(function () {
            Route::get('/', [BranchesController::class, 'index']);
            Route::get('/data', [BranchesController::class, 'data']);
            Route::get('/search', [BranchesController::class, 'search']);
            Route::post('/', [BranchesController::class, 'store']);
            Route::get('/structure-orgranization/{branch}', [BranchesController::class, 'structureOrgranization']);
            Route::get(
                '/structure-orgranization/data/{branch}',
                [BranchesController::class, 'structureOrgranizationData']
            );
            Route::get('/show/{branch}', [BranchesController::class, 'show']);
            Route::post('/update/{branch}', [BranchesController::class, 'update']);
            Route::post('/destroy/', [BranchesController::class, 'destroy']);
            Route::post('/import', [BranchesController::class, 'import']);
        });

        //contact
        Route::prefix('contact')->group(function () {
            Route::get('/', [ContactController::class, 'index']);
            Route::get('/data', [ContactController::class, 'data']);
            Route::get('/search', [ContactController::class, 'search']);
            Route::get('/branch/data', [ContactController::class, 'branchData']);
            Route::get('filter/branch/data/{branch}', [ContactController::class, 'filterByBranch']);
            Route::post('/', [ContactController::class, 'store']);
            Route::get('/edit/{contact}', [ContactController::class, 'edit']);
            Route::post('/update/{contact}', [ContactController::class, 'update']);
            Route::post('/destroy', [ContactController::class, 'destroy']);
        });
        //product
        Route::prefix('product')->group(function () {
            Route::get('/', [ProductController::class, 'index']);
            Route::get('/data', [ProductController::class, 'data']);
            Route::get('/search', [ProductController::class, 'search']);
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/show/{product}', [ProductController::class, 'show']);
            Route::post('/update/{product}', [ProductController::class, 'update']);
            Route::post('/destroy', [ProductController::class, 'destroy']);
        });

        Route::prefix('service-categories')->group(function () {
            Route::get('/', [ServicesCategoryController::class, 'index']);
            Route::get('/data', [ServicesCategoryController::class, 'data']);
            Route::get('/search', [ServicesCategoryController::class, 'search']);
            Route::post('/', [ServicesCategoryController::class, 'store']);
            Route::get('/show/{serviceCategory}', [ServicesCategoryController::class, 'show']);
            Route::post('/update/{serviceCategory}', [ServicesCategoryController::class, 'update']);
            Route::post('/destroy', [ServicesCategoryController::class, 'destroy']);
        });

        Route::prefix('department')->group(function () {
            Route::get('/', [DepartmentController::class, 'index']);
            Route::get('/data', [DepartmentController::class, 'data']);
            Route::get('/search', [DepartmentController::class, 'search']);
            Route::post('/', [DepartmentController::class, 'store']);
            Route::get('/{department}', [DepartmentController::class, 'edit']);
            Route::post('/{department}', [DepartmentController::class, 'update']);
            Route::delete('/{department}', [DepartmentController::class, 'destroy']);
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::get('/data', [RoleController::class, 'rolesData']);
            Route::get('/permissions/data', [RoleController::class, 'getPermission']);
            Route::get('/create', [RoleController::class, 'create']);
            Route::get('/search', [RoleController::class, 'search']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/edit/{role}', [RoleController::class, 'edit']);
            Route::get('/show/{role}', [RoleController::class, 'show']);
            Route::post('/update/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'destroy']);
        });
    });

    //utility
    Route::prefix('utility')->group(function () {
        Route::prefix('letter-head')->group(function () {
            Route::get('/', [LetterHeadController::class, 'index']);
            Route::post('/update/{letterHead}', [LetterHeadController::class, 'update']);
        });

        Route::prefix('company-profile')->group(function () {
            Route::get('/', [CompanyProfileController::class, 'index']);
            Route::post('/update/{companyProfile}', [CompanyProfileController::class, 'update']);
        });

        Route::prefix('user-profile')->group(function () {
            Route::get('/profile-detail', [UserProfileController::class, 'index']);
            Route::get('/notification-detail', [UserProfileController::class, 'notificationDetail']);
            Route::get('/change-profile', [UserProfileController::class, 'changeProfile']);
            Route::post('/update-profile/{user}', [UserProfileController::class, 'updateProfilePic']);
            Route::post('/update-password/{user}', [UserProfileController::class, 'updatePassword']);
            Route::get('/identity-information', [UserProfileController::class, 'identityInformationPage']);
            Route::get('/identity-information/data', [UserProfileController::class, 'identityInformation']);
            Route::get('/job-information', [UserProfileController::class, 'jobInformationPage']);
            Route::get('/job-information/data', [UserProfileController::class, 'jobInformation']);

            Route::prefix('leaves-and-permission')->group(function () {
                Route::get('/', [LeaveAndPermissionController::class, 'index']);
                Route::get('/data', [LeaveAndPermissionController::class, 'data']);
                Route::get('/search', [LeaveAndPermissionController::class, 'search']);
                Route::get('/create', [LeaveAndPermissionController::class, 'create']);
                Route::post('/', [LeaveAndPermissionController::class, 'store']);
                Route::get('/{leaveAndPermission}', [LeaveAndPermissionController::class, 'edit']);
                Route::post('/{leaveAndPermission}', [LeaveAndPermissionController::class, 'update']);
                Route::delete('/{leaveAndPermission}', [LeaveAndPermissionController::class, 'destroy']);
            });
        });
    });

    Route::prefix('/journals')->group(function () {
        Route::controller(GeneralJournalController::class)
            ->prefix('general-journal')->group(function () {
                Route::get('/', 'index');
                Route::get('/periode', 'period');
                Route::get('/detail/{time}', 'detail');
                Route::get('/detail/data/{time}', 'detailJournal');
                Route::get('/search', 'search');
            });

        Route::controller(GeneralLedgerController::class)
            ->prefix('general-ledger')->group(function () {
                Route::get('/', 'index');
                Route::get('/data', 'data');
                Route::get('/detail/{account}', 'detail');
                Route::get('detail-akun/{account}', 'detailAkunData');
            });
    });

    Route::prefix('inventory')->group(function () {
        Route::prefix('goods')->group(function () {
            Route::get('/unit-types/data', [GoodsController::class, 'getUnitTypesData']);
            Route::get('/', [GoodsController::class, 'index']);
            Route::get('/data', [GoodsController::class, 'data']);
            Route::get('/branch/data', [GoodsController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [GoodsController::class, 'filterByBranch']);
            Route::get('/search', [GoodsController::class, 'search']);
            Route::get('/create', [GoodsController::class, 'create']);
            Route::get('/related-accounts/data', [GoodsController::class, 'getRelatedAccounts']);
            Route::post('/', [GoodsController::class, 'store']);
            Route::get('/edit/{goods}', [GoodsController::class, 'edit']);
            Route::get('/get-selected-unit-type/{goods}', [GoodsController::class, 'getSelectedUnitType']);
            Route::get('/get-selected-sub-account/{goods}', [GoodsController::class, 'getSelectedSubAccount']);
            Route::post('/update/{goods}', [GoodsController::class, 'update']);
            Route::post('/confirm/{goods}', [GoodsController::class, 'confirm']);
            Route::get('/detail/data/{goods}', [GoodsController::class, 'show']);
            Route::get('/used-items-detail/{goods}', [GoodsController::class, 'useItemDetail']);
            Route::delete('/{goods}', [GoodsController::class, 'destroy']);
        });

        Route::prefix('used-items')->group(function () {
            Route::get('/get-used-items/{goods}', [UsedItemsController::class, 'getUsedItems']);
            Route::post('/save-used-items/{goods}', [UsedItemsController::class, 'usedItems']);
            Route::get('account/asset/data', [UsedItemsController::class, 'getAssetAccount']);
        });

        Route::prefix('unit-types')->group(function () {
            Route::get('/', [UnitTypesController::class, 'index']);
            Route::get('/data', [UnitTypesController::class, 'data']);
            Route::get('/search', [UnitTypesController::class, 'search']);
            Route::post('/', [UnitTypesController::class, 'store']);
            Route::get('/{unitType}', [UnitTypesController::class, 'edit']);
            Route::post('/{unitType}', [UnitTypesController::class, 'update']);
            Route::delete('/{unitType}', [UnitTypesController::class, 'destroy']);
        });
    });

    Route::prefix('journal-adjustment')->group(function () {
        Route::prefix('initial-journal')->group(function () {
            Route::get('/', [InitialJournalController::class, 'index']);
            Route::get('/data', [InitialJournalController::class, 'data']);
            Route::get('/search', [InitialJournalController::class, 'search']);
            Route::get('account/debit/data', [InitialJournalController::class, 'selectAccountDebit']);
            Route::get('account/credit/data', [InitialJournalController::class, 'selectAccountCredit']);
            Route::post('/', [InitialJournalController::class, 'store']);
            Route::get('edit/{initialJournal}', [InitialJournalController::class, 'edit']);
            Route::get(
                '/debit-account/selected/{initialJournal}',
                [InitialJournalController::class, 'selectedDebitAccount']
            );
            Route::get(
                '/credit-account/selected/{initialJournal}',
                [InitialJournalController::class, 'selectedCreditAccount']
            );
            Route::post('/update/{initialJournal}', [InitialJournalController::class, 'update']);
            Route::post('/confirm/{initialJournal}', [InitialJournalController::class, 'confirm']);
            Route::delete('/{initialJournal}', [InitialJournalController::class, 'destroy']);
        });

        Route::prefix('adjustment')->group(function () {
            Route::get('/', [JournalAdjustmentController::class, 'index']);
            Route::get('/data', [JournalAdjustmentController::class, 'data']);
            Route::get('initial-journal/data', [JournalAdjustmentController::class, 'initialJournalData']);
            Route::post('/', [JournalAdjustmentController::class, 'store']);
            Route::get('/{journalAdjustment}', [JournalAdjustmentController::class, 'edit']);
            Route::get(
                '/initial-journal/selected/{journalAdjustment}',
                [JournalAdjustmentController::class, 'selectedInitialJournal']
            );
            Route::post('/{journalAdjustment}', [JournalAdjustmentController::class, 'update']);
            Route::delete('/{journalAdjustment}', [JournalAdjustmentController::class, 'destroy']);
        });
    });

    Route::prefix('income-transactions')->group(function () {
        Route::prefix('offering-letters')->group(function () {
            Route::get('/', [OfferingLettersController::class, 'index']);
            Route::get('/data', [OfferingLettersController::class, 'data']);
            Route::get('/branch/data', [OfferingLettersController::class, 'branchData']);
            Route::get('filter/branch/data/{branch}', [OfferingLettersController::class, 'filterByBranch']);
            Route::get('/search', [OfferingLettersController::class, 'search']);
            Route::get('/create', [OfferingLettersController::class, 'create']);
            Route::get('/contact/data', [OfferingLettersController::class, 'getContactData']);
            Route::get('/service-categories/data', [OfferingLettersController::class, 'getServicesCategoriesData']);
            Route::post('/', [OfferingLettersController::class, 'store']);
            Route::post('/update/{offeringLetter}', [OfferingLettersController::class, 'update']);
            Route::get('/view-file/{offeringLetter}', [OfferingLettersController::class, 'viewFile']);
            Route::get('/detail/{offeringLetter}', [OfferingLettersController::class, 'show']);
            Route::get('/edit/{offeringLetter}', [OfferingLettersController::class, 'edit']);
            Route::get(
                '/get-selected-contact/{offeringLetter}',
                [OfferingLettersController::class, 'getSelectedContact']
            );
            Route::get(
                '/get-selected-services/{offeringLetter}',
                [OfferingLettersController::class, 'getOfferingLetterProductServices']
            );
            Route::post('/confirm/{offeringLetter}', [OfferingLettersController::class, 'confirm']);
            Route::delete('/{offeringLetter}', [OfferingLettersController::class, 'destroy']);
            Route::get('/export-pdf/{offeringLetter}', [OfferingLettersController::class, 'exportToPDF']);
        });
        Route::prefix('fab')->group(function () {
            Route::get('/', [FabController::class, 'index']);
            Route::get('/data', [FabController::class, 'data']);
            Route::get('/search', [FabController::class, 'search']);
            Route::get('/branch/data', [FabController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [FabController::class, 'filterByBranch']);
            Route::get('/create', [FabController::class, 'create']);
            Route::get('/product/data', [FabController::class, 'getProductData']);
            Route::get('/contact/data', [FabController::class, 'contactData']);
            Route::get('/services-categories/data', [FabController::class, 'getServicesCategoriesData']);
            Route::post('/', [FabController::class, 'store']);
            Route::get('/view-file/{fab}', [FabController::class, 'viewFile']);
            Route::get('/detail/{fab}', [FabController::class, 'detail']);
            Route::get('/edit/{fab}', [FabController::class, 'edit']);
            Route::get('/get-selected-contact/{fab}', [FabController::class, 'selectedContact']);
            Route::get('/get-selected-services/{fab}', [FabController::class, 'selectedServices']);
            Route::post('/update/{fab}', [FabController::class, 'update']);
            Route::post('/confirm/{fab}', [FabController::class, 'confirm']);
            Route::get('/jurnal-entry/{fab}', [FabController::class, 'jurnalEntry']);
            Route::delete('/{fab}', [FabController::class, 'destroy']);
            Route::get('/export-pdf/{fab}', [FabController::class, 'exportPDF']);
        });
        Route::prefix('bast')->group(function () {
            Route::get('/', [BastController::class, 'index']);
            Route::get('/data', [BastController::class, 'data']);
            Route::get('/search', [BastController::class, 'search']);
            Route::get('/create', [BastController::class, 'create']);
            Route::get('/branch/data', [BastController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [BastController::class, 'filterByBranch']);
            Route::get('/contact/data', [BastController::class, 'contactData']);
            Route::post('/', [BastController::class, 'store']);
            Route::get('/view-file/{bast}', [BastController::class, 'viewFile']);
            Route::get('/detail/{bast}', [BastController::class, 'detail']);
            Route::get('/edit/{bast}', [BastController::class, 'edit']);
            Route::get('/get-selected-contact/{bast}', [BastController::class, 'getSelectedContact']);
            Route::get('/get-selected-products/{bast}', [BastController::class, 'getProductBast']);
            Route::post('/update/{bast}', [BastController::class, 'update']);
            Route::post('/confirm/{bast}', [BastController::class, 'confirm']);
            Route::delete('/{bast}', [BastController::class, 'destroy']);
            Route::get('/export-pdf/{bast}', [BastController::class, 'exportToPDF']);
        });
        Route::prefix('invoice')->group(function () {
            Route::get('/', [InvoiceController::class, 'index']);
            Route::get('/create', [InvoiceController::class, 'create']);
            Route::get('/account/data', [InvoiceController::class, 'getAccountData']);
            Route::get('/contact/data', [InvoiceController::class, 'getContact']);
            Route::get('/branch/data', [InvoiceController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [InvoiceController::class, 'filterByBranch']);
            Route::post('/generate-invoice/', [InvoiceController::class, 'store']);
            Route::get('/data', [InvoiceController::class, 'data']);
            Route::get('/search', [InvoiceController::class, 'search']);
            Route::get('/detail/{invoice}', [InvoiceController::class, 'detail']);
            Route::get('/edit/{invoice}', [InvoiceController::class, 'edit']);
            Route::get('/contact/selected/{invoice}', [InvoiceController::class, 'getSelectedContact']);
            Route::get('/account/selected/{invoice}', [InvoiceController::class, 'getSelectedSubAccount']);
            Route::get('/services/selected/{invoice}', [InvoiceController::class, 'getSelectedInvoiceProductServices']);
            Route::post('/update/{invoice}', [InvoiceController::class, 'update']);
            Route::post('/confirm/{invoice}', [InvoiceController::class, 'confirm']);
            Route::post('/update-payment-status/{invoice}', [InvoiceController::class, 'updatePaymentStatus']);
            Route::get('/jurnal-entry/{invoice}', [InvoiceController::class, 'jurnalEntry']);
            Route::get('/view-file/{invoice}', [InvoiceController::class, 'viewFile']);
            Route::get('/export-pdf/{invoice}', [InvoiceController::class, 'exportToPDF']);
            Route::delete('/{invoice}', [InvoiceController::class, 'destroy']);
        });
    });

    Route::prefix('/expenditure-transactions/expenditure')->group(function () {
        Route::get('/', [ExpenditureController::class, 'index']);
        Route::get('/data', [ExpenditureController::class, 'data']);
        Route::get('/search', [ExpenditureController::class, 'search']);
        Route::get('/branch/data', [ExpenditureController::class, 'branchData']);
        Route::get('/filter/branch/data/{branch}', [ExpenditureController::class, 'filterByBranch']);
        Route::get('get-debit-account', [ExpenditureController::class, 'debitAccount']);
        Route::get('get-credit-account', [ExpenditureController::class, 'creditAccount']);
        Route::get('/selected-debit-account/{expenditure}', [ExpenditureController::class, 'selectedDebitAccount']);
        Route::get('/selected-credit-account/{expenditure}', [ExpenditureController::class, 'selectedCreditAccount']);
        Route::post('/', [ExpenditureController::class, 'store']);
        Route::get('/{expenditure}', [ExpenditureController::class, 'edit']);
        Route::post('/{expenditure}', [ExpenditureController::class, 'update']);
        Route::post('/confirm/{expenditure}', [ExpenditureController::class, 'confirm']);
        Route::delete('/{expenditure}', [ExpenditureController::class, 'destroy']);
    });

    Route::prefix('/setting')->group(function () {
        Route::prefix('/menu')->group(function () {
            Route::get('/', [MenuController::class, 'index']);
            Route::get('/data', [MenuController::class, 'data']);
        });
    });

    Route::prefix('/adms')->group(function () {
        Route::prefix('/fp-devices')->group(function () {
            Route::get('/', [FpDevicesController::class, 'index']);
            Route::get('/data', [FpDevicesController::class, 'data']);
            Route::get('/search', [FpDevicesController::class, 'search']);
            Route::post('/', [FpDevicesController::class, 'store']);
            Route::get('/{fpDevice}', [FpDevicesController::class, 'edit']);
            Route::get('/branch/data', [FpDevicesController::class, 'getBranchData']);
            Route::get('/branch/selected/{fpDevice}', [FpDevicesController::class, 'selectedBranchData']);
            Route::post('/{fpDevice}', [FpDevicesController::class, 'update']);
            Route::delete('/{fpDevice}', [FpDevicesController::class, 'destroy']);
        });


        Route::prefix('attendances')->group(function () {
            Route::get('/', [AttendancesController::class, 'index']);
            Route::get('/data', [AttendancesController::class, 'data']);
        });

        Route::prefix('/work-time')->group(function () {
            Route::get('/', [WorkTimeController::class, 'index']);
            Route::get('/data', [WorkTimeController::class, 'data']);
            Route::get('/user/data', [WorkTimeController::class, 'getUserData']);
            Route::get('/search', [WorkTimeController::class, 'search']);
            Route::post('/', [WorkTimeController::class, 'store']);
            Route::get('/{workTime}', [WorkTimeController::class, 'edit']);
            Route::post('/{workTime}', [WorkTimeController::class, 'update']);
            Route::delete('/{workTime}', [WorkTimeController::class, 'destroy']);
            Route::post('assign-work-time/{workTime}', [WorkTimeController::class, 'assignWorkTime']);
            Route::get('/user/selected/{workTime}', [WorkTimeController::class, 'getSelectedUserWorkTime']);
            Route::get('/detail/{workTime}', [WorkTimeController::class, 'detail']);
            Route::get('/detail/data/{workTime}', [WorkTimeController::class, 'detailData']);
        });


        Route::prefix('/attendances-summary')->group(function () {
            Route::get('/', [AttendanceSummaryController::class, 'index']);
            Route::get('/period-data', [AttendanceSummaryController::class, 'selectPeriodData']);
            Route::get('/detail/{time}', [AttendanceSummaryController::class, 'detail']);
            Route::get('/detail/data/01-{month}-{year}', [AttendanceSummaryController::class, 'detailData']);
            Route::get('/detail/data/search', [AttendanceSummaryController::class, 'searchDetailData']);
            Route::post('/detail/data/filter-date/{month}/{year}', [AttendanceSummaryController::class, 'filterDate']);
            Route::post('/detail/data/assign-sp/{employeeId}',
                [AttendanceSummaryController::class, 'assignSPToEmployee']);
            Route::get('/detail/data/user/detail/{month}/{year}/{employeeId}',
                [AttendanceSummaryController::class, 'attendanceSummaryDetailForOneMonthBasedOnUserId']);
        });
    });
});

Route::prefix('/iclock')->group(function () {
    Route::get('cdata', [IclockController::class, 'handshake']);
    Route::post('cdata', [IclockController::class, 'receiveRecords']);
    Route::get('test', [IclockController::class, 'test']);
    Route::get('getrequest', [IclockController::class, 'getrequest']);
});

