<?php

use App\Http\Controllers\Accounting\Asset\AssetDepreciationController;
use App\Http\Controllers\Accounting\JournalAdjustment\InitialJournalController;
use App\Http\Controllers\Accounting\JournalAdjustment\JournalAdjustmentController;
use App\Http\Controllers\Accounting\Journals\CashflowStatementController;
use App\Http\Controllers\Accounting\Journals\FinancialReportController;
use App\Http\Controllers\Accounting\Journals\GeneralJournalController;
use App\Http\Controllers\Accounting\Journals\GeneralLedgerController;
use App\Http\Controllers\Accounting\Journals\IncomeStatementController;
use App\Http\Controllers\Accounting\Journals\TrialBalanceController;
use App\Http\Controllers\Accounting\Transaction\BastController;
use App\Http\Controllers\Accounting\Transaction\ExpenditureController;
use App\Http\Controllers\Accounting\Transaction\FabController;
use App\Http\Controllers\Accounting\Transaction\InitialBalanceController;
use App\Http\Controllers\Accounting\Transaction\InvoiceController;
use App\Http\Controllers\Accounting\Transaction\OfferingLettersController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HRIS\Attendances\AttendancesController;
use App\Http\Controllers\HRIS\Attendances\AttendanceSummaryController;
use App\Http\Controllers\HRIS\Attendances\FpDevicesController;
use App\Http\Controllers\HRIS\Attendances\IclockController;
use App\Http\Controllers\HRIS\Attendances\WorkTimeController;
use App\Http\Controllers\HRIS\Correspondence\ContractManagementController;
use App\Http\Controllers\HRIS\Correspondence\ManageUserLeavesController;
use App\Http\Controllers\HRIS\Correspondence\SKController;
use App\Http\Controllers\HRIS\Correspondence\SPController;
use App\Http\Controllers\HRIS\EmployeesData\EducationCertificateController;
use App\Http\Controllers\HRIS\EmployeesData\EducationController;
use App\Http\Controllers\HRIS\EmployeesData\FamilyInformationController;
use App\Http\Controllers\HRIS\EmployeesData\HealthInformationController;
use App\Http\Controllers\HRIS\EmployeesData\IdentityInformationController;
use App\Http\Controllers\HRIS\EmployeesData\JobExperiencesController;
use App\Http\Controllers\HRIS\EmployeesData\JobInformationController;
use App\Http\Controllers\HRIS\EmployeesData\UserController;
use App\Http\Controllers\HRIS\Payroll\GeneratePayrollController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances\MealAllowanceController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances\OvertimeAllowanceController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances\PositionAllowancesController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances\ThrAllowancesController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Allowances\TransportationAllowanceController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Bonuses\ProjectBonusController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Bonuses\SalesBonusController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions\AdditionalDeductionController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions\NinePastFiveteenLateController;
use App\Http\Controllers\HRIS\Payroll\PayrollComponent\Deductions\SLADeductionController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\BPJSKetController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\CutOffController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\PayrollAllowanceController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\PayrollController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\PayrollHistoryController;
use App\Http\Controllers\HRIS\Payroll\PayrollConfigurations\PayrollScheduleController;
use App\Http\Controllers\HRIS\PermissionController;
use App\Http\Controllers\Inventory\BoQ\BoqController;
use App\Http\Controllers\Inventory\FieldAssets\JointClosureController;
use App\Http\Controllers\Inventory\FOCable\FOCableController;
use App\Http\Controllers\Inventory\FOCable\FOCableMapController;
use App\Http\Controllers\Inventory\Stock\GoodsController;
use App\Http\Controllers\Inventory\Stock\InventoryCategoryController;
use App\Http\Controllers\Inventory\Stock\UnitTypesController;
use App\Http\Controllers\Inventory\Stock\UsedItemsController;
use App\Http\Controllers\Inventory\ODP\ODPController;
use App\Http\Controllers\Inventory\ODP\ODPMapController;
use App\Http\Controllers\Inventory\Pole\PoleController;
use App\Http\Controllers\Inventory\Pole\PoleMapController;
use App\Http\Controllers\Master\AccountTransactionsController;
use App\Http\Controllers\Master\Finance\AccountController;
use App\Http\Controllers\Master\Finance\AssetController;
use App\Http\Controllers\Master\Finance\TaxSettingController;
use App\Http\Controllers\Master\General\BranchesController;
use App\Http\Controllers\Master\General\BroadbandPacketController;
use App\Http\Controllers\Master\General\ContactController;
use App\Http\Controllers\Master\General\DepartmentController;
use App\Http\Controllers\Master\General\NationalHolidayController;
use App\Http\Controllers\Master\General\ProductController;
use App\Http\Controllers\Master\General\RoleController;
use App\Http\Controllers\Master\General\ServicesCategoryController;
use App\Http\Controllers\Master\Operational\JointClosureCodeController;
use App\Http\Controllers\Master\Operational\SupplierController;
use App\Http\Controllers\UserProfile\AttendanceRecordController;
use App\Http\Controllers\UserProfile\UserLeaveAndPermissionController;
use App\Http\Controllers\UserProfile\UserProfileController;
use App\Http\Controllers\UserProfile\Utilities\CompanyProfileController;
use App\Http\Controllers\UserProfile\Utilities\LetterHeadController;
use App\Http\Controllers\UserProfile\Utilities\NotificationsController;
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


Route::prefix('/iclock')->group(function () {
    Route::post('/cdata', [IclockController::class, 'receiveRecords']);
    Route::get('/cdata', [IclockController::class, 'handshake']);
    Route::get('test', [IclockController::class, 'test']);
    Route::get('getrequest', [IclockController::class, 'getrequest']);
});


Route::group(['middleware' => ['auth']], static function () {
    //dashboard
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/summary', [HomeController::class, 'summary']);

    Route::prefix('/manage-users')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/data', [UserController::class, 'data']);
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
            Route::post('/import', [UserController::class, 'import']);
            Route::get('/detail/{user}', [UserController::class, 'detail']);
            Route::get('/department/data', [UserController::class, 'getDepartmentData']);
            Route::get('/absent/data/{user}', [UserController::class, 'getAbsentData']);
            Route::post('/update/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
            Route::post('/change-status/{user}', [UserController::class, 'changeStatusActive']);
            Route::get('/filter', [UserController::class, 'filter']);
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
            Route::get('/contract-file/{user}', [JobInformationController::class, 'contractFile']);
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
            Route::delete('/destroy', [PermissionController::class, 'destroy']);
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
            Route::get('/users/data/selected/{sp}', [SpController::class, 'selectedUserdata']);
            Route::get('/list-of-reason/{sp}', [SpController::class, 'getListOfReason']);
            Route::get('/{sp}', [SpController::class, 'edit']);
            Route::post('/{sp}', [SpController::class, 'update']);
            Route::get('/confirm/{sp}', [SpController::class, 'confirm']);
            Route::delete('/{sp}', [SpController::class, 'destroy']);
            Route::get('export-pdf/{sp}', [SPController::class, 'exportToPDF']);
            Route::get('/user/current-sp/{user}', [SPController::class, 'getCurrentSp']);
            Route::get('/show/{sp}', [SPController::class, 'show']);
        });


        Route::prefix('contract-management')->group(function () {
            Route::get('/', [ContractManagementController::class, 'index']);
            Route::get('/data', [ContractManagementController::class, 'data']);
            Route::get('/branch/data', [ContractManagementController::class, 'getBranchData']);
            Route::get('/filter', [ContractManagementController::class, 'filter']);
            Route::get('/search', [ContractManagementController::class, 'search']);
            Route::post('/store', [ContractManagementController::class, 'store']);
            Route::get('/{contractManagement}', [ContractManagementController::class, 'edit']);
            Route::post('/{contractManagement}', [ContractManagementController::class, 'update']);
            Route::delete('/{contractManagement}', [ContractManagementController::class, 'destroy']);
            Route::post('/extend-contract/{user}', [ContractManagementController::class, 'extendContract']);
            Route::get('/contract-pdf/{user}', [ContractManagementController::class, 'contractFile']);
        });


        Route::prefix('sk')->group(function () {
            Route::get('/', [SKController::class, 'index']);
            Route::get('/data', [SKController::class, 'data']);
            Route::get('/search', [SKController::class, 'search']);
            Route::get('/users/data', [SKController::class, 'getUserData']);
            Route::get('/roles/data', [SKController::class, 'getRoleData']);
            Route::get('/branch/data', [SKController::class, 'getBranchData']);
            Route::post('/', [SKController::class, 'store']);
            Route::get('/{sk}', [SKController::class, 'edit']);
            Route::post('/{sk}', [SKController::class, 'update']);
            Route::get('/export-pdf/{sk}', [SKController::class, 'exportToPDF']);
            Route::get('/selected-branch/{sk}', [SKController::class, 'getSelectedBranch']);
            Route::get('/selected-role/{sk}', [SKController::class, 'getSelectedRole']);
            Route::get('/selected-user/{sk}', [SKController::class, 'getSelectedUser']);
        });
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::get('/detail', [NotificationsController::class, 'detail']);
        Route::post('/mark-as-read', [NotificationsController::class, 'markAsRead']);
    });


    Route::prefix('general-master-data')->group(function () {
        Route::prefix('branch')->group(function () {
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
            Route::post('/destroy', [DepartmentController::class, 'destroy']);
            Route::post('/{department}', [DepartmentController::class, 'update']);
        });


        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index']);
            Route::get('/data', [RoleController::class, 'rolesData']);
            Route::get('/departments/data', [RoleController::class, 'getDepartments']);
            Route::get('/permissions/data', [RoleController::class, 'getPermission']);
            Route::get('/departments/data/selected/{role}', [RoleController::class, 'getSelectedDepartment']);
            Route::get('/create', [RoleController::class, 'create']);
            Route::get('/search', [RoleController::class, 'search']);
            Route::post('/', [RoleController::class, 'store']);
            Route::get('/edit/{role}', [RoleController::class, 'edit']);
            Route::get('/show/{role}', [RoleController::class, 'show']);
            Route::post('/update/{role}', [RoleController::class, 'update']);
            Route::delete('/{role}', [RoleController::class, 'destroy']);
            Route::get('/detail/{role}', [RoleController::class, 'detail']);
            Route::get('/detail/associated-users/{role}', [RoleController::class, 'associatedUsers']);
        });

        Route::prefix('broadband-packet')->group(function () {
            Route::get('/', [BroadbandPacketController::class, 'index']);
            Route::get('/data', [BroadbandPacketController::class, 'data']);
            Route::get('/search', [BroadbandPacketController::class, 'search']);
            Route::post('/', [BroadbandPacketController::class, 'store']);
            Route::get('/{broadbandPacket}', [BroadbandPacketController::class, 'edit']);
            Route::post('/destroy', [BroadbandPacketController::class, 'destroy']);
            Route::post('/{broadbandPacket}', [BroadbandPacketController::class, 'update']);
        });
    });


    Route::prefix('finances-master-data')->group(function () {
        Route::prefix('account')->group(function () {
            Route::get('/', [AccountController::class, 'index']);
            Route::post('/create-child/{account}', [AccountController::class, 'createChildAccount']);
            Route::get('/data', [AccountController::class, 'data']);
            Route::get('/search', [AccountController::class, 'search']);
            Route::post('/', [AccountController::class, 'store']);
            Route::post('/import', [AccountController::class, 'import']);
            Route::post('/destroy', [AccountController::class, 'destroy']);
            Route::get('/edit/{account}', [AccountController::class, 'edit']);
            Route::post('/update/{account}', [AccountController::class, 'update']);
        });
        Route::prefix('initial-balances')->group(function () {
            Route::get('/filter', [InitialBalanceController::class, 'filter']);
            Route::get('/', [InitialBalanceController::class, 'index']);
            Route::get('/data', [InitialBalanceController::class, 'data']);
            Route::get('/account/data', [InitialBalanceController::class, 'getAccountData']);
            Route::get('/branch/data', [InitialBalanceController::class, 'getBranchData']);
            Route::post('/', [InitialBalanceController::class, 'store']);
            Route::get('/{accountTransaction}', [InitialBalanceController::class, 'edit']);
            Route::get('/branch/selected/{accountTransaction}', [InitialBalanceController::class, 'selectedBranch']);
            Route::get(
                '/account/selected/{accountTransaction}',
                [InitialBalanceController::class, 'selectedAccountData']
            );

            Route::post('/destroy', [InitialBalanceController::class, 'destroy']);
            Route::post('/{accountTransaction}', [InitialBalanceController::class, 'update']);
        });


        Route::prefix('tax-settings')->group(function () {
            Route::get('/', [TaxSettingController::class, 'index']);
            Route::get('/data', [TaxSettingController::class, 'data']);
            Route::get('/search', [TaxSettingController::class, 'search']);
            Route::post('/', [TaxSettingController::class, 'store']);
            Route::get('/{taxSetting}', [TaxSettingController::class, 'edit']);
            Route::post('/destroy', [TaxSettingController::class, 'destroy']);
            Route::post('/update/{taxSetting}', [TaxSettingController::class, 'update']);
        });
        Route::prefix('assets')->group(function () {
            Route::post('/destroy', [AssetController::class, 'destroy']);
            Route::get('/', [AssetController::class, 'index']);
            Route::get('/data', [AssetController::class, 'data']);
            Route::get('/search', [AssetController::class, 'search']);
            Route::get('/branch/data', [AssetController::class, 'getBranchData']);
            Route::get('/debit-account/data', [AssetController::class, 'getDebitAccount']);
            Route::get('/credit-account/data', [AssetController::class, 'getCreditAccount']);
            Route::get('/account/selected/{asset}', [AssetController::class, 'selectedAccount']);
            Route::get('/branch/selected/{asset}', [AssetController::class, 'selectedBranch']);
            Route::post('/', [AssetController::class, 'store']);
            Route::get('/{asset}', [AssetController::class, 'edit']);
            Route::post('/update/{asset}', [AssetController::class, 'update']);
            Route::post('/confirm/{asset}', [AssetController::class, 'confirm']);
            Route::get('/detail/{asset}', [AssetController::class, 'detail']);
            Route::get('/detail/data/{asset}', [AssetController::class, 'getDetailData']);
            Route::post('/import', [AssetController::class, 'import']);
        });
    });


    Route::prefix('operational-master-data')->group(function () {
        Route::prefix('joint-closures-code')->group(function () {
            Route::get('/', [JointClosureCodeController::class, 'index']);
            Route::get('/data', [JointClosureCodeController::class, 'data']);
            Route::get('/branch/data', [JointClosureCodeController::class, 'getBranchData']);
            Route::get('/{jointClosureCode}', [JointClosureCodeController::class, 'edit']);
            Route::get('/branch/selected/{jointClosureCode}', [JointClosureCodeController::class, 'selectedBranch']);
            Route::post('/', [JointClosureCodeController::class, 'store']);
            Route::post('/destroy', [JointClosureCodeController::class, 'destroy']);
            Route::post('/{jointClosureCode}', [JointClosureCodeController::class, 'update']);
        });


        Route::prefix('suppliers')->group(function () {
            Route::get('/', [SupplierController::class, 'index']);
            Route::get('/data', [SupplierController::class, 'data']);
            Route::get('/search', [SupplierController::class, 'search']);
            Route::post('/', [SupplierController::class, 'store']);
            Route::get('/{supplier}', [SupplierController::class, 'edit']);
            Route::post('/destroy', [SupplierController::class, 'destroy']);
            Route::get('/{supplier}', [SupplierController::class, 'update']);
        });


        Route::prefix('inventory-categories')->group(function () {
            Route::get('/', [InventoryCategoryController::class, 'index']);
            Route::get('/data', [InventoryCategoryController::class, 'data']);
            Route::get('/search', [InventoryCategoryController::class, 'search']);
            Route::post('/', [InventoryCategoryController::class, 'store']);
            Route::get('/{inventoryCategory}', [InventoryCategoryController::class, 'edit']);
            Route::post('/destroy', [InventoryCategoryController::class, 'destroy']);
            Route::post('/{inventoryCategory}', [InventoryCategoryController::class, 'update']);
        });
    });

    Route::prefix('operational')->group(function () {
        Route::prefix('odp')->group(function () {
            Route::get('/', [ODPController::class, 'index']);
            Route::get('/data', [ODPController::class, 'data']);
            Route::get('/search', [ODPController::class, 'search']);
            Route::get('/create', [ODPController::class, 'create']);
            Route::get('/branch/data', [ODPController::class, 'getBranchData']);
            Route::get('/branch/selected/{odp}', [ODPController::class, 'selectedBranchData']);
            Route::get('/export', [ODPController::class, 'export']);
            Route::get('/odp-area/selected/{odp}', [ODPController::class, 'getSelectedODPArea']);
            Route::post('/', [ODPController::class, 'store']);
            Route::get('/{odp}', [ODPController::class, 'edit']);
            Route::post('/import', [ODPController::class, 'import']);
            Route::post('/destroy', [ODPController::class, 'destroy']);
            Route::post('/{odp}', [ODPController::class, 'update']);
        });


        Route::prefix('odp-map')->group(function () {
            Route::get('/', [ODPMapController::class, 'index']);
            Route::get('/data', [ODPMapController::class, 'data']);
            Route::get('/filter', [ODPMapController::class, 'filter']);
        });


        Route::prefix('poles-map')->group(function () {
            Route::get('/', [PoleMapController::class, 'index']);
            Route::get('/data', [PoleMapController::class, 'data']);
            Route::get('/filter', [PoleMapController::class, 'filter']);
        });

        Route::prefix('fo-cables-map')->group(function () {
            Route::get('/', [FOCableMapController::class, 'index']);
            Route::get('/data', [FOCableMapController::class, 'data']);
            Route::get('/filter', [FOCableMapController::class, 'filter']);
        });


        Route::prefix('fo-cables')->group(function () {
            Route::get('/', [FOCableController::class, 'index']);
            Route::get('/data', [FOCableController::class, 'data']);
            Route::get('/search', [FOCableController::class, 'search']);
            Route::get('/create', [FOCableController::class, 'create']);
            Route::get('/branch/data', [FoCableController::class, 'getBranchData']);
            Route::get('/branch/selected/{FOCable}', [FoCableController::class, 'selectedBranchData']);
            Route::post('/', [FOCableController::class, 'store']);
            Route::post('/destroy', [FOCableController::class, 'destroy']);
            Route::post('/import', [FOCableController::class, 'import']);
            Route::get('/export', [FOCableController::class, 'export']);
            Route::get('/{FOCable}', [FOCableController::class, 'edit']);
            Route::post('update/{FOCable}', [FOCableController::class, 'update']);
            Route::post('/destroy', [FOCableController::class, 'destroy']);
        });

        Route::prefix('poles')->group(function () {
            Route::get('/', [PoleController::class, 'index']);
            Route::get('/data', [PoleController::class, 'data']);
            Route::get('/create', [PoleController::class, 'create']);
            Route::get('/search', [PoleController::class, 'search']);
            Route::get('/branch/data', [PoleController::class, 'getBranchData']);
            Route::get('/branch/selected/{pole}', [PoleController::class, 'selectedBranch']);
            Route::post('/', [PoleController::class, 'store']);
            Route::post('/destroy', [PoleController::class, 'destroy']);
            Route::post('/import', [PoleController::class, 'import']);
            Route::get('/export', [PoleController::class, 'export']);
            Route::get('/{pole}', [PoleController::class, 'edit']);
            Route::post('/{pole}', [PoleController::class, 'update']);
        });

        Route::prefix('joint-closures')->group(function () {
            Route::get('/', [JointClosureController::class, 'index']);
            Route::get('/data', [JointClosureController::class, 'data']);
            Route::get('/code/data', [JointClosureController::class, 'getJointClosuresCode']);
            Route::get('/fo-cables/data', [JointClosureController::class, 'getFoCable']);
            Route::get('/code/selected/{jointClosure}', [JointClosureController::class, 'getSelectedCode']);
            Route::get('/fo-cable/selected/{jointClosure}', [JointClosureController::class, 'getSelectedFoCable']);
            Route::get('/search', [JointClosureController::class, 'search']);
            Route::get('/create', [JointClosureController::class, 'create']);
            Route::post('/', [JointClosureController::class, 'store']);
            Route::post('/destroy', [JointClosureController::class, 'destroy']);
            Route::post('/import', [JointClosureController::class, 'import']);
            Route::get('/export', [JointClosureController::class, 'export']);
            Route::get('/{jointClosure}', [JointClosureController::class, 'edit']);
            Route::post('/{jointClosure}', [JointClosureController::class, 'update']);
        });

        Route::prefix('core-data')->group(function () {
        });

        Route::prefix('coverage-area')->group(function () {
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
            Route::get('/identity-information/data', [UserProfileController::class, 'identityInformation']);
            Route::get('/job-information/data', [UserProfileController::class, 'jobInformation']);
            Route::get('/sp', [UserProfileController::class, 'spPage']);
            Route::get('/sp/data', [UserProfileController::class, 'spData']);

            Route::prefix('leaves-and-permission')->group(function () {
                Route::get('/', [UserLeaveAndPermissionController::class, 'index']);
                Route::get('/data', [UserLeaveAndPermissionController::class, 'data']);
                Route::get('/search', [UserLeaveAndPermissionController::class, 'search']);
                Route::get('/create', [UserLeaveAndPermissionController::class, 'create']);
                Route::post('/', [UserLeaveAndPermissionController::class, 'store']);
                Route::get('/{leaveAndPermission}', [UserLeaveAndPermissionController::class, 'edit']);
                Route::post('/{leaveAndPermission}', [UserLeaveAndPermissionController::class, 'update']);
                Route::delete('/{leaveAndPermission}', [UserLeaveAndPermissionController::class, 'destroy']);
            });


            Route::prefix('attendance-records')->group(function () {
                Route::get('/', [AttendanceRecordController::class, 'index']);
                Route::get('/data/{user?}', [AttendanceRecordController::class, 'data']);
                Route::get('/filter/{user?}', [AttendanceRecordController::class, 'filter']);
            });
        });
    });

    Route::prefix('/journals')->group(function () {
        Route::controller(GeneralJournalController::class)
            ->prefix('general-journal')->group(function () {
                Route::get('/', 'index');
                Route::get('/data', 'data');
                Route::get('/search', 'search');
            });

        Route::prefix('general-journal')->group(function () {
            Route::get('/', [GeneralJournalController::class, 'index']);
            Route::get('/data', [GeneralJournalController::class, 'data']);
            Route::get('/branch/data', [GeneralJournalController::class, 'getBranchData']);
            Route::get('/filter', [GeneralJournalController::class, 'filter']);
        });


        Route::prefix('general-ledger')->group(function () {
            Route::get('/', [GeneralLedgerController::class, 'index']);
            Route::get('/data', [GeneralLedgerController::class, 'data']);
            Route::get('/detail/{account}', [GeneralLedgerController::class, 'detail']);
            Route::get('detail-akun/{account}', [GeneralLedgerController::class, 'detailAccountTransaction']);
            Route::get('/filter/{account}', [GeneralLedgerController::class, 'filter']);
        });


        Route::prefix('trial-balance')->group(function () {
            Route::get('/', [TrialBalanceController::class, 'index']);
            Route::get('/branch/data', [TrialBalanceController::class, 'getBranchData']);
            Route::get('/data', [TrialBalanceController::class, 'data']);
            Route::get('/filter', [TrialBalanceController::class, 'filter']);
        });


        Route::prefix('financial-report')->group(function () {
            Route::get('/', [FinancialReportController::class, 'index']);
            Route::get('/data', [FinancialReportController::class, 'data']);
            Route::get('/current-assets/data', [FinancialReportController::class, 'getCurrentAsset']);
            Route::get('/fixed-assets/data', [FinancialReportController::class, 'getFixedAsset']);
            Route::get('/branch/data', [FinancialReportController::class, 'getBranchData']);
            Route::get('/filter', [FinancialReportController::class, 'filter']);
            Route::get(
                '/accumulated-depreciation-of-fixed-assets-account',
                [FinancialReportController::class, 'accumulatedDepreciationOfFixedAssetsAccount']
            );
        });

        Route::prefix('assets-depreciation')->group(function () {
            Route::get('/', [AssetDepreciationController::class, 'index']);
            Route::get('/data', [AssetDepreciationController::class, 'data']);
        });


        Route::prefix('income-statement')->group(function () {
            Route::get('/', [IncomeStatementController::class, 'index']);
            Route::get('/data', [IncomeStatementController::class, 'data']);
        });


        Route::prefix('cashflow-statement')->group(function () {
            Route::get('/', [CashflowStatementController::class, 'index']);
            Route::get('/data', [CashflowStatementController::class, 'data']);
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


        Route::prefix('/boq')->group(function () {
            Route::get('/', [BoqController::class, 'index']);
            Route::get('/data', [BoqController::class, 'data']);
            Route::get('/search', [BoqController::class, 'search']);
            Route::get('/create', [BoqController::class, 'create']);
            Route::post('/', [BoqController::class, 'store']);
            Route::get('/unit-type/data', [BoqController::class, 'getUnitTypes']);
            Route::get('/unit-type/selected/{id}', [BoqController::class, 'selectedUnitType']);
            Route::get('/users/data', [BoqController::class, 'getUserData']);
            Route::get('/detail/{boq}', [BoqController::class, 'detail']);
            Route::get('/get-boq-commodity/{boq}', [BoqController::class, 'getBoqCommodity']);
            Route::get('/get-project-timeline/{boq}', [BoqController::class, 'getProjectTimeline']);
            Route::get('/unit-type/selected/{boq}', [BoqController::class, 'selectedUnitType']);
            Route::get('/users/selected/{id}', [BoqController::class, 'selectedUser']);
            Route::get('/{boq}', [BoqController::class, 'edit']);
            Route::post('/{boq}', [BoqController::class, 'update']);
            Route::post('/destroy', [BoqController::class, 'destroy']);
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

    Route::prefix('/adms')->group(function () {
        Route::prefix('/national-holiday')->group(function () {
            Route::get('/', [NationalHolidayController::class, 'index']);
            Route::get('/data', [NationalHolidayController::class, 'data']);
            Route::post('/', [NationalHolidayController::class, 'generateHoliday']);
        });
        Route::prefix('/fp-devices')->group(function () {
            Route::get('/', [FpDevicesController::class, 'index']);
            Route::get('/data', [FpDevicesController::class, 'data']);
            Route::get('/search', [FpDevicesController::class, 'search']);
            Route::post('/', [FpDevicesController::class, 'store']);
            Route::get('/{fpDevice}', [FpDevicesController::class, 'edit']);
            Route::get('/branch/data', [FpDevicesController::class, 'getBranchData']);
            Route::get('/branch/selected/{fpDevice}', [FpDevicesController::class, 'selectedBranchData']);
            Route::post('/destroy', [FpDevicesController::class, 'destroy']);
            Route::post('/{fpDevice}', [FpDevicesController::class, 'update']);
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
            Route::get(
                '/detail/data/search/{workTime}',
                [WorkTimeController::class, 'searchDetailData']
            );
            Route::delete(
                '/detail/data/destroy/{userWorkTime}',
                [WorkTimeController::class, 'destroyDetailWorktimeUser']
            );
        });

        Route::prefix('/attendances-summary')->group(function () {
            Route::get('/', [AttendanceSummaryController::class, 'index']);
            Route::get('/data', [AttendanceSummaryController::class, 'data']);
            Route::get('/search', [AttendanceSummaryController::class, 'search']);
            Route::post('/filter-date', [AttendanceSummaryController::class, 'filterByDate']);
            Route::get('/detail/{user}', [AttendanceSummaryController::class, 'detail']);
            Route::get('/detail/data/{user}', [AttendanceSummaryController::class, 'detailData']);
            Route::get('/detail/filter/{user}', [AttendanceSummaryController::class, 'filterByDate']);
            Route::get('/detail/correction/{datePeriod}/{user}', [AttendanceSummaryController::class, 'correction']);
            Route::post(
                '/detail/correction/save/{user}/{datePeriod?}',
                [AttendanceSummaryController::class, 'saveCorrection']
            );
        });
    });

    Route::get('/love-you-with-all-my-heart', [AttendanceSummaryController::class, 'absenTanpaMesin'])->name(
        'absenTanpaMesin'
    );
    Route::post('/love-you-with-all-my-heart/simpan', [AttendanceSummaryController::class, 'simpanAbsenTanpaMesin']);


    Route::prefix('payroll/setting')->group(function () {
        Route::get('/', [PayrollController::class, 'index']);
        Route::get('/roles/data', [PayrollController::class, 'getRolesData']);
        Route::get('/payroll-schedule/data', [PayrollScheduleController::class, 'data']);
        Route::post('payroll-schedule/', [PayrollScheduleController::class, 'store']);
        Route::get('/payroll-allowance/data', [PayrollAllowanceController::class, 'data']);
        Route::post('/payroll-allowance/', [PayrollAllowanceController::class, 'store']);
        Route::delete('/payroll-allowance/{payrollAllowance}', [PayrollAllowanceController::class, 'destroy']);
        Route::get('/bpjs-ket/data', [BPJSKetController::class, 'data']);
        Route::get('/bpjs-ket/{bpjsKet}', [BPJSketController::class, 'edit']);
        Route::post('/bpjs-ket/{bpjsKet}', [BpjsketController::class, 'update']);
        Route::get('/cut-off/data', [CutOffController::class, 'data']);
        Route::post('/cut-off/save', [CutOffController::class, 'update']);


        Route::prefix('allowances/position')->group(function () {
            Route::get('/', [PositionAllowancesController::class, 'index']);
            Route::get('/data', [PositionAllowancesController::class, 'data']);
            Route::get('/user/data', [PositionAllowancesController::class, 'getUser']);
            Route::get(
                '/user/selected/{jobInformation}',
                [PositionAllowancesController::class, 'getSelectedUser']
            );
            Route::get('/search', [PositionAllowancesController::class, 'search']);
            Route::post('/', [PositionAllowancesController::class, 'store']);
            Route::get('/{jobInformation}', [PositionAllowancesController::class, 'edit']);
            Route::post('/{jobInformation}', [PositionAllowancesController::class, 'update']);
        });


        Route::prefix('allowances/meal')->group(function () {
            Route::get('/', [MealAllowanceController::class, 'index']);
            Route::get('/data', [MealAllowanceController::class, 'data']);
            Route::get('/search', [MealAllowanceController::class, 'search']);
            Route::get('/user/data', [MealAllowanceController::class, 'getUser']);
            Route::get('/user/selected/{userHasMealAllowance}', [MealAllowanceController::class, 'selectedUser']);
            Route::get('/search', [MealAllowanceController::class, 'search']);
            Route::post('/', [MealAllowanceController::class, 'store']);
            Route::get('/{userHasMealAllowance}', [MealAllowanceController::class, 'edit']);
            Route::post('/destroy', [MealAllowanceController::class, 'destroy']);
            Route::post('/{userHasMealAllowance}', [MealAllowanceController::class, 'update']);
        });


        Route::prefix('allowances/transportation')->group(function () {
            Route::get('/', [TransportationAllowanceController::class, 'index']);
            Route::get('/data', [TransportationAllowanceController::class, 'data']);
            Route::get('/search', [TransportationAllowanceController::class, 'search']);
            Route::get('/user/data', [TransportationAllowanceController::class, 'getUserData']);
            Route::get(
                '/user/selected/{userHasTransportationAllowance}',
                [TransportationAllowanceController::class, 'getSelectedUser']
            );
            Route::post('/', [TransportationAllowanceController::class, 'store']);
            Route::get('/{userHasTransportationAllowance}', [TransportationAllowanceController::class, 'edit']);
            Route::post('/destroy', [TransportationAllowanceController::class, 'destroy']);
            Route::post('/{userHasTransportationAllowance}', [TransportationAllowanceController::class, 'update']);
        });


        Route::prefix('allowances/overtime')->group(function () {
            Route::get('/', [OvertimeAllowanceController::class, 'index']);
            Route::get('/data', [OvertimeAllowanceController::class, 'data']);
            Route::get('/search', [OvertimeAllowanceController::class, 'search']);
            Route::get('/user/data', [OvertimeAllowanceController::class, 'getUserData']);
            Route::get(
                '/user/selected/{userHasOvertime}',
                [OvertimeAllowanceController::class, 'getSelectedUser']
            );
            Route::post('/', [OvertimeAllowanceController::class, 'store']);
            Route::get('/{userHasOvertime}', [OvertimeAllowanceController::class, 'edit']);
            Route::post('/destroy', [OvertimeAllowanceController::class, 'destroy']);
            Route::post('update/{userHasOvertime}', [OvertimeAllowanceController::class, 'update']);
        });


        Route::prefix('allowances/thr')->group(function () {
            Route::get('/', [ThrAllowancesController::class, 'index']);
            Route::get('/data', [ThrAllowancesController::class, 'data']);
            Route::get('/search', [ThrAllowancesController::class, 'search']);
            Route::post('/', [ThrAllowancesController::class, 'store']);
        });


        Route::prefix('deduction/sla')->group(function () {
            Route::get('/', [SLADeductionController::class, 'index']);
            Route::get('/data', [SLADeductionController::class, 'data']);
            Route::get('/search', [SLADeductionController::class, 'search']);
            Route::get('/user/data', [SLADeductionController::class, 'getUserData']);
            Route::get('/user/selected/{SLADeduction}', [SLADeductionController::class, 'getSelectedUser']);
            Route::post('/', [SLADeductionController::class, 'store']);
            Route::get('/{SLADeduction}', [SLADeductionController::class, 'edit']);
            Route::post('/destroy', [SLADeductionController::class, 'destroy']);
            Route::post('/{SLADeduction}', [SLADeductionController::class, 'update']);
        });


        Route::prefix('deduction/nine-past-fiveteen-late')->group(function () {
            Route::get('/', [NinePastFiveteenLateController::class, 'index']);
            Route::get('/data', [NinePastFiveteenLateController::class, 'data']);
            Route::get('/search', [NinePastFiveteenLateController::class, 'search']);
            Route::get('/user/data', [NinePastFiveteenLateController::class, 'getUserData']);
            Route::get(
                '/user/selected/{ninePastFiveTeenLateDeduction}',
                [NinePastFiveteenLateController::class, 'getSelectedUser']
            );
            Route::post('/', [NinePastFiveteenLateController::class, 'store']);
            Route::get('/{ninePastFiveTeenLateDeduction}', [NinePastFiveteenLateController::class, 'edit']);
            Route::post('/destroy', [NinePastFiveteenLateController::class, 'destroy']);
            Route::post('/{ninePastFiveTeenLateDeduction}', [NinePastFiveteenLateController::class, 'update']);
        });


        Route::prefix('deduction/additional-deduction')->group(function () {
            Route::get('/', [AdditionalDeductionController::class, 'index']);
            Route::get('/data', [AdditionalDeductionController::class, 'data']);
            Route::get('/search', [AdditionalDeductionController::class, 'search']);
            Route::get('/user/data', [AdditionalDeductionController::class, 'getUserData']);
            Route::get(
                '/user/selected/{additionalDeduction}',
                [AdditionalDeductionController::class, 'getSelectedUser']
            );
            Route::post('/', [AdditionalDeductionController::class, 'store']);
            Route::get('/{additionalDeduction}', [AdditionalDeductionController::class, 'edit']);
            Route::post('/destroy', [AdditionalDeductionController::class, 'destroy']);
            Route::post('/{additionalDeduction}', [AdditionalDeductionController::class, 'update']);
        });


        Route::prefix('benefit/sales-bonus')->group(function () {
            Route::get('/', [SalesBonusController::class, 'index']);
            Route::get('/data', [SalesBonusController::class, 'data']);
            Route::get('/search', [SalesBonusController::class, 'search']);
            Route::get('/user/data', [SalesBonusController::class, 'getUserData']);
            Route::get('/user/selected/{saleBonus}', [SalesBonusController::class, 'getSelectedUser']);
            Route::get('/broadband-packet/data', [SalesBonusController::class, 'getBroadbandPacket']);
            Route::get(
                '/broadband-packet/selected/{saleBonus}',
                [SalesBonusController::class, 'getSelectedBroadbandPacket']
            );
            Route::post('/import', [SalesBonusController::class, 'import']);
            Route::post('/', [SalesBonusController::class, 'store']);
            Route::get('/{saleBonus}', [SalesBonusController::class, 'edit']);
            Route::post('/destroy', [SalesBonusController::class, 'destroy']);
            Route::post('/{saleBonus}', [SalesBonusController::class, 'update']);
        });


        Route::prefix('benefit/project-bonus')->group(function () {
            Route::get('/', [ProjectBonusController::class, 'index']);
            Route::get('/create', [ProjectBonusController::class, 'create']);
            Route::get('/data', [ProjectBonusController::class, 'data']);
            Route::get('/search', [ProjectBonusController::class, 'search']);
            Route::get('/user/data', [ProjectBonusController::class, 'getUserData']);
            Route::post('/', [ProjectBonusController::class, 'store']);
            Route::get('/{projectBonus}', [ProjectBonusController::class, 'edit']);
            Route::get(
                'user-has-project-bonus/{projectBonus}',
                [ProjectBonusController::class, 'getUserhasProjectBonus']
            );
            Route::get(
                'user-has-project-bonus/show/{projectBonus}',
                [ProjectBonusController::class, 'getSelectedProjectBonus']
            );
            Route::post('/destroy', [ProjectBonusController::class, 'destroy']);
            Route::post('/{projectBonus}', [ProjectBonusController::class, 'update']);
            Route::get('/view-file/{projectBonus}', [ProjectBonusController::class, 'viewFile']);
        });
    });


    Route::prefix('payroll/generate')->group(function () {
        Route::get('/', [GeneratePayrollController::class, 'index']);
        Route::post('/', [GeneratePayrollController::class, 'generatePayroll']);
    });


    Route::prefix('payroll/payroll-history')->group(function () {
        Route::get('/', [PayrollHistoryController::class, 'index']);
        Route::get('/data', [PayrollHistoryController::class, 'data']);
    });
});


