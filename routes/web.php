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
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\BastController;
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\ExpenditureController;
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\FabController;
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\InvoiceController;
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\OfferingLetterController;
use App\Http\Controllers\Accounting\Transaction\IncomeTransactions\PurchaseOrderController;
use App\Http\Controllers\Accounting\VendorPayrollController;
use App\Http\Controllers\AccountTransactionController;
use App\Http\Controllers\Area\AreaController;
use App\Http\Controllers\Area\AreaDetailController;
use App\Http\Controllers\AttendanceManualRequestController;
use App\Http\Controllers\BAAController;
use App\Http\Controllers\DraftStockController;
use App\Http\Controllers\HRIS\Attendances\AttendanceSummaryController;
use App\Http\Controllers\HRIS\Attendances\EmployeeScheduleController;
use App\Http\Controllers\HRIS\Attendances\FpDevicesController;
use App\Http\Controllers\HRIS\Attendances\IclockController;
use App\Http\Controllers\HRIS\Attendances\WorkTimeController;
use App\Http\Controllers\HRIS\Correspondence\ContractManagementController;
use App\Http\Controllers\HRIS\Correspondence\LeaveAndPermissionController;
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
use App\Http\Controllers\HRIS\RoleHierarchyController;
use App\Http\Controllers\InitialInventoryBalanceController;
use App\Http\Controllers\ItemCatalogController;
use App\Http\Controllers\Master\Accounting\AccountCategoryController;
use App\Http\Controllers\Master\Accounting\AccountController;
use App\Http\Controllers\Master\Accounting\Asset\AssetController;
use App\Http\Controllers\Master\Accounting\InitialBalanceController;
use App\Http\Controllers\Master\Accounting\TaxSettingController;
use App\Http\Controllers\Master\Common\BranchController;
use App\Http\Controllers\Master\Common\BroadbandPacketController;
use App\Http\Controllers\Master\Common\CompanyController;
use App\Http\Controllers\Master\Common\ContactController;
use App\Http\Controllers\Master\Common\DepartmentController;
use App\Http\Controllers\Master\Common\NationalHolidayController;
use App\Http\Controllers\Master\Common\RoleController;
use App\Http\Controllers\Master\Common\ServiceCategoryManagerController;
use App\Http\Controllers\Master\Common\SKLController;
use App\Http\Controllers\Master\Operational\ItemCategoryController;
use App\Http\Controllers\Master\Operational\ItemCollectionController;
use App\Http\Controllers\Master\Operational\PSBController;
use App\Http\Controllers\Master\Operational\SupplierController;
use App\Http\Controllers\MustReorderStockController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMutationController;
use App\Http\Controllers\StockWithdrawalController;
use App\Http\Controllers\StockWithdrawalItemController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UnitTypeController;
use App\Http\Controllers\UserProfile\AttendanceRecordController;
use App\Http\Controllers\UserProfile\UserProfileController;
use App\Http\Controllers\UserProfile\Utilities\CompanyProfileController;
use App\Http\Controllers\UserProfile\Utilities\LetterHeadController;
use App\Http\Controllers\UserProfile\Utilities\NotificationsController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Jmrashed\Zkteco\Lib\ZKTeco;

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


Route::get('/phpinfo', function () {
    phpinfo();
});


Route::get('/test', function () {
    ini_set('max_execution_time', 300);
    $zk = new ZKTeco('103.211.160.26');
    $connected = $zk->connect();
    $attendanceLog = $zk->getAttendance();

});

Route::get('/', function () {
    return redirect('home');
});


Auth::routes();


Route::prefix('/iclock')->group(function () {
    Route::post('/cdata', [IclockController::class, 'receiveRecords']);
    Route::get('/cdata', [IclockController::class, 'handshake']);
    Route::get('test', [IclockController::class, 'test']);
    Route::get('/getrequest', [IclockController::class, 'getRequest']);
    Route::get('/get-attendance-via-push-sdk', [IclockController::class, 'getAttendanceViaPushSDK']);
});


Route::group(['middleware' => ['auth']], static function () {
    //dashboard
    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('/transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/final-status', [TransactionController::class, 'finalStatus']);
        Route::post('/destroy', [TransactionController::class, 'destroy']);
        Route::get('/data', [TransactionController::class, 'data']);
        Route::get('/filter', [TransactionController::class, 'filter']);
        Route::get('/search', [TransactionController::class, 'search']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/item-transaction-qty-in-this-month', [TransactionController::class, 'getItemTransactionQtyInThisMonth']);
        Route::get('/{transaction}', [TransactionController::class, 'edit']);
        Route::post('/{transaction}', [TransactionController::class, 'update']);
        Route::post('/lock-transaction/{transaction}', [TransactionController::class, 'lockTransaction']);
        Route::post('/confirm/{transaction}', [TransactionController::class, 'confirm']);

    });

    Route::prefix('/manage-users')->group(function () {
        Route::prefix('role-hierarchy')->group(function () {
            Route::get('/', [RoleHierarchyController::class, 'index']);
            Route::get('/data', [RoleHierarchyController::class, 'data']);
        });
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
            Route::post('/destroy', [UserController::class, 'destroy']);
            Route::get('/edit/{user}', [UserController::class, 'edit']);
            Route::get('/show/{user}', [UserController::class, 'show']);
            Route::post('/import', [UserController::class, 'import']);
            Route::get('/detail/{user}', [UserController::class, 'detail']);
            Route::get('/department/data', [UserController::class, 'getDepartmentData']);
            Route::get('/absent/data/{user}', [UserController::class, 'getAbsentData']);
            Route::post('/update/{user}', [UserController::class, 'update']);
            Route::post('/change-status/{user}', [UserController::class, 'changeStatusActive']);
            Route::get('/filter', [UserController::class, 'filter']);
            Route::get('companies/selected/{user}', [UserController::class, 'getSelectedCompany']);
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
            Route::post('/destroy', [PermissionController::class, 'destroy']);
            Route::post('/update/{permission}', [PermissionController::class, 'update']);
        });
        Route::prefix('leaves')->group(function () {
            Route::post('/destroy', [LeaveAndPermissionController::class, 'destroy']);
            Route::get('/leaves-left', [LeaveAndPermissionController::class, 'getTotalLeavesLeft']);
            Route::get('/', [LeaveAndPermissionController::class, 'index']);
            Route::get('/data', [LeaveAndPermissionController::class, 'data']);
            Route::get('/search', [LeaveAndPermissionController::class, 'search']);
            Route::post('/', [LeaveAndPermissionController::class, 'store']);
            Route::post('/{leaveAndPermission}', [LeaveAndPermissionController::class, 'changeStatus']);
            Route::get('/users/data', [LeaveAndPermissionController::class, 'getUserData']);
            Route::get('/branch/data', [LeaveAndPermissionController::class, 'getBranchData']);
            Route::get('/users/selected/{leaveAndPermission}', [LeaveAndPermissionController::class, 'selectedUserData']);
            Route::get('/filter', [LeaveAndPermissionController::class, 'filter']);
            Route::post('/', [LeaveAndPermissionController::class, 'store']);
            Route::get('/edit/{leaveAndPermission}', [LeaveAndPermissionController::class, 'edit']);
            Route::post('/update/{leaveAndPermission}', [LeaveAndPermissionController::class, 'update']);
        });
        Route::prefix('sp')->group(function () {
            Route::get('/', [SPController::class, 'index']);
            Route::get('/data', [SpController::class, 'data']);
            Route::get('/search', [SpController::class, 'search']);
            Route::get('/filter', [SPController::class, 'filter']);
            Route::get('/users/data', [SpController::class, 'getUserData']);
            Route::get('/sp-pic/data', [SPController::class, 'getSPPIC']);
            Route::get('/branch/data', [SpController::class, 'getBranchData']);
            Route::get('/create', [SpController::class, 'create']);
            Route::post('/', [SpController::class, 'store']);
            Route::get('/punished-by/selected/{sp}', [SpController::class, 'selectedPunishedBy']);
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
            Route::post('/', [SKController::class, 'store']);
            Route::get('/{sk}', [SKController::class, 'edit']);
            Route::post('/{sk}', [SKController::class, 'update']);
            Route::get('/export-pdf/{sk}', [SKController::class, 'exportToPDF']);
        });
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index']);
        Route::get('/detail', [NotificationsController::class, 'detail']);
        Route::post('/mark-as-read', [NotificationsController::class, 'markAsRead']);
    });


    Route::prefix('/master')->group(function () {

        Route::prefix('/common')->group(function () {
            Route::prefix('area')->group(function () {
                Route::get('/', [AreaController::class, 'index']);
                Route::get('/data', [AreaController::class, 'data']);
                Route::get('/search', [AreaController::class, 'search']);
                Route::get('/filter', [AreaController::class, 'filter']);
                Route::post('/', [AreaController::class, 'store']);
                Route::post('/destroy', [AreaController::class, 'destroy']);
                Route::get('/{area}', [AreaController::class, 'edit']);
                Route::post('/{area}', [AreaController::class, 'update']);
                Route::get('detail/{area}', [AreaController::class, 'detail']);
            });
            Route::prefix('area-detail')->group(function () {
                Route::post('/destroy', [AreaDetailController::class, 'destroy']);
                Route::get('/data/{area}', [AreaDetailController::class, 'data']);
                Route::get('/users/data/{area}', [AreaDetailController::class, 'getUser']);
                Route::post('/{area}', [AreaDetailController::class, 'assignUser']);
                Route::get('/search/{area}', [AreaDetailController::class, 'search']);
                Route::get('users/selected/{area}', [AreaDetailController::class, 'selectedUser']);
                Route::get('/show/{user}', [AreaDetailController::class, 'show']);
                Route::post('/save-week-holiday/{user}', [AreaDetailController::class, 'assignWeekHoliday']);
            });

            Route::prefix('branch')->group(function () {
                Route::get('/', [BranchController::class, 'index']);
                Route::get('/data', [BranchController::class, 'data']);
                Route::get('/search', [BranchController::class, 'search']);
                Route::post('/', [BranchController::class, 'store']);
                Route::get('/show/{branch}', [BranchController::class, 'show']);
                Route::get('/sub-branch/detail/{branch}', [BranchController::class, 'subBranchDetail']);
                Route::post('/sub-branch/store', [BranchController::class, 'storeChildren']);
                Route::post('/sub-branch/update/{branch}', [BranchController::class, 'updateChildren']);
                Route::post('update/{branch}', [BranchController::class, 'update']);
                Route::post('/destroy/', [BranchController::class, 'destroy']);
                Route::delete('/sub-branch/destroy/{branch}', [BranchController::class, 'destroyChildren']);
            });

            Route::prefix('unit-types')->group(function () {
                Route::get('/', [UnitTypeController::class, 'index']);
                Route::get('/data', [UnitTypeController::class, 'data']);
                Route::get('/search', [UnitTypeController::class, 'search']);
                Route::post('/', [UnitTypeController::class, 'store']);
                Route::get('/show/{unitType}', [UnitTypeController::class, 'edit']);
                Route::post('/destroy', [UnitTypeController::class, 'destroy']);
                Route::post('/update/{unitType}', [UnitTypeController::class, 'update']);
            });

            Route::prefix('skl')->group(function () {
                Route::get('/', [SKLController::class, 'index']);
                Route::get('/data', [SKLController::class, 'data']);
                Route::get('/search', [SKLController::class, 'search']);
                Route::post('/', [SKLController::class, 'store']);
                Route::get('/{skl}', [SKLController::class, 'edit']);
                Route::post('/destroy', [SKLController::class, 'destroy']);
                Route::post('/{skl}', [SKLController::class, 'update']);
            });

            Route::prefix('contact')->group(function () {
                Route::get('/', [ContactController::class, 'index']);
                Route::get('/data', [ContactController::class, 'data']);
                Route::get('/search', [ContactController::class, 'search']);
                Route::get('/branch/data', [ContactController::class, 'branchData']);
                Route::get('filter/branch/data/{branch}', [ContactController::class, 'filterByBranch']);
                Route::post('/', [ContactController::class, 'store']);
                Route::get('/edit/{contact}', [ContactController::class, 'edit']);
                Route::post('/destroy', [ContactController::class, 'destroy']);
                Route::post('/update/{contact}', [ContactController::class, 'update']);
            });
            Route::prefix('service-categories')->group(function () {
                Route::get('/', [ServiceCategoryManagerController::class, 'index']);
                Route::get('/data', [ServiceCategoryManagerController::class, 'data']);
                Route::get('/search', [ServiceCategoryManagerController::class, 'search']);
                Route::post('/', [ServiceCategoryManagerController::class, 'store']);
                Route::post('/destroy', [ServiceCategoryManagerController::class, 'destroy']);
                Route::get('/show/{serviceCategory}', [ServiceCategoryManagerController::class, 'show']);
                Route::post('/update/{serviceCategory}', [ServiceCategoryManagerController::class, 'update']);
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
                Route::get('/permissions/data', [RoleController::class, 'getPermissions']);
                Route::get('/permissions/search', [RoleController::class, 'searchPermission']);
                Route::get('/departments/data/selected/{role}', [RoleController::class, 'getSelectedDepartment']);
                Route::get('/permissions/data/selected/{role}', [RoleController::class, 'getSelectedPermission']);
                Route::get('/create', [RoleController::class, 'create']);
                Route::get('/search', [RoleController::class, 'search']);
                Route::post('/', [RoleController::class, 'store']);
                Route::get('/edit/{role}', [RoleController::class, 'edit']);
                Route::get('/show/{role}', [RoleController::class, 'show']);
                Route::post('/update/{role}', [RoleController::class, 'update']);
                Route::delete('/{role}', [RoleController::class, 'destroy']);
            });

            Route::prefix('broadband-packets')->group(function () {
                Route::get('/', [BroadbandPacketController::class, 'index']);
                Route::get('/data', [BroadbandPacketController::class, 'data']);
                Route::get('/search', [BroadbandPacketController::class, 'search']);
                Route::post('/', [BroadbandPacketController::class, 'store']);
                Route::get('/{broadbandPacket}', [BroadbandPacketController::class, 'edit']);
                Route::post('/destroy', [BroadbandPacketController::class, 'destroy']);
                Route::post('/{broadbandPacket}', [BroadbandPacketController::class, 'update']);
            });

            Route::prefix('companies')->group(function () {
                Route::get('/', [CompanyController::class, 'index']);
                Route::get('/data', [CompanyController::class, 'data']);
                Route::get('/search', [CompanyController::class, 'search']);
                Route::post('/', [CompanyController::class, 'store']);
                Route::get('/{company}', [CompanyController::class, 'edit']);
                Route::post('/update/{company}', [CompanyController::class, 'update']);
                Route::post('/destroy', [CompanyController::class, 'destroy']);
                Route::post('/{company}', [CompanyController::class, 'update']);
            });
        });
        Route::prefix('/accounting')->group(function () {
            Route::prefix('account-categories')->group(function () {
                Route::get('/', [AccountCategoryController::class, 'index']);
                Route::get('/data', [AccountCategoryController::class, 'data']);
                Route::get('/search', [AccountCategoryController::class, 'search']);
                Route::post('/', [AccountCategoryController::class, 'store']);
                Route::post('/create-child/{accountCategory}', [AccountCategoryController::class, 'storeChild']);
                Route::post('/import', [AccountCategoryController::class, 'import']);
                Route::post('/destroy', [AccountCategoryController::class, 'destroy']);
                Route::get('/edit/{accountCategory}', [AccountCategoryController::class, 'edit']);
                Route::post('/update/{accountCategory}', [AccountCategoryController::class, 'update']);
                Route::post('/update-child/{accountCategory}', [AccountCategoryController::class, 'updateChild']);
            });

            Route::prefix('accounts')->group(function () {
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
                Route::get('/search', [InitialBalanceController::class, 'search']);
                Route::get('/account/data', [InitialBalanceController::class, 'getAccountData']);
                Route::post('/', [InitialBalanceController::class, 'store']);
                Route::get('/{account}', [InitialBalanceController::class, 'edit']);
                Route::get(
                    '/account/selected/{account}',
                    [InitialBalanceController::class, 'selectedAccountData']
                );
                Route::post('/destroy', [InitialBalanceController::class, 'destroy']);
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
                Route::get('/detail/data/{asset}', [AssetController::class, 'depreciationData']);
            });

            Route::prefix('initial-inventory-balances')->group(function () {
                Route::get('/', [InitialInventoryBalanceController::class, 'index']);
                Route::get('/data', [InitialInventoryBalanceController::class, 'data']);
                Route::get('/search', [InitialInventoryBalanceController::class, 'search']);
                Route::post('/', [InitialInventoryBalanceController::class, 'store']);
                Route::post('/destroy', [InitialInventoryBalanceController::class, 'destroy']);
                Route::post('/confirm', [InitialInventoryBalanceController::class, 'confirm']);
                Route::get('/{initialInventoryBalance}', [InitialInventoryBalanceController::class, 'edit']);
                Route::post('/{initialInventoryBalance}', [InitialInventoryBalanceController::class, 'update']);
            });
        });


        Route::prefix('operational')->group(function () {
            Route::prefix('item-categories')->group(function () {
                Route::get('/', [ItemCategoryController::class, 'index']);
                Route::get('/data', [ItemCategoryController::class, 'data']);
                Route::get('/search', [ItemCategoryController::class, 'search']);
                Route::post('/', [ItemCategoryController::class, 'store']);
                Route::get('/{itemCategory}', [ItemCategoryController::class, 'edit']);
                Route::post('/destroy', [ItemCategoryController::class, 'destroy']);
                Route::post('/{itemCategory}', [ItemCategoryController::class, 'update']);
            });
            Route::prefix('/items')->group(function () {

                Route::prefix('archives')->group(function () {
                    Route::get('/', [ItemCollectionController::class, 'archived']);
                    Route::get('/data', [ItemCollectionController::class, 'archivedData']);
                    Route::get('/search', [ItemCollectionController::class, 'archivedSearch']);
                    Route::get('/filter', [ItemCollectionController::class, 'archivedFilter']);
                    Route::post('/restore', [ItemCollectionController::class, 'restore']);
                });


                Route::get('/', [ItemCollectionController::class, 'index']);
                Route::get('/data', [ItemCollectionController::class, 'data']);
                Route::get('/filter', [ItemCollectionController::class, 'filter']);
                Route::get('/search', [ItemCollectionController::class, 'search']);
                Route::post('/', [ItemCollectionController::class, 'store']);
                Route::get('/non-building-group-details', [ItemCollectionController::class, 'nonBuildingGroupDetails']);
                Route::post('/destroy', [ItemCollectionController::class, 'destroy']);
                Route::get('/{itemCollection}', [ItemCollectionController::class, 'edit']);
                Route::post('/update/{itemCollection}', [ItemCollectionController::class, 'update']);
            });


            Route::prefix('suppliers')->group(function () {
                Route::get('/', [SupplierController::class, 'index']);
                Route::get('/data', [SupplierController::class, 'data']);
                Route::get('/search', [SupplierController::class, 'search']);
                Route::post('/', [SupplierController::class, 'store']);
                Route::get('/{supplier}', [SupplierController::class, 'edit']);
                Route::post('/destroy', [SupplierController::class, 'destroy']);
                Route::post('/{supplier}', [SupplierController::class, 'update']);
            });

            Route::prefix('psb')->group(function () {
                Route::get('/', [PSBController::class, 'index']);
                Route::get('/data', [PSBController::class, 'data']);
                Route::get('/area/data', [PSBController::class, 'getAreaData']);
                Route::get('/search', [PSBController::class, 'search']);
                Route::post('/', [PSBController::class, 'store']);
                Route::get('/{psb}', [PSBController::class, 'edit']);
                Route::post('/{psb}', [PSBController::class, 'update']);
                Route::post('/destroy', [PSBController::class, 'destroy']);
            });

        });
    });


    Route::prefix('finances-master-data')->group(function () {


    });


    Route::prefix('operational-master-data')->group(function () {


        Route::prefix('warehouses')->group(function () {
            Route::get('/', [WarehouseController::class, 'index']);
            Route::get('/data', [WarehouseController::class, 'data']);
            Route::get('/search', [WarehouseController::class, 'search']);
            Route::post('/', [WarehouseController::class, 'store']);
            Route::get('/{warehouse}', [WarehouseController::class, 'edit']);
            Route::post('/destroy', [WarehouseController::class, 'destroy']);
            Route::post('/{warehouse}', [WarehouseController::class, 'update']);
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
            Route::get('/leaves', [UserProfileController::class, 'leavePage']);
            Route::get('/leaves/data', [UserProfileController::class, 'leavesData']);
            Route::get('/leaves/search', [UserProfileController::class, 'searchLeaves']);

            Route::prefix('attendance-records')->group(function () {
                Route::get('/', [AttendanceRecordController::class, 'index']);
                Route::get('/data/{user?}', [AttendanceRecordController::class, 'data']);
                Route::get('/filter/{user?}', [AttendanceRecordController::class, 'filter']);
            });

            Route::prefix('/carried-stock')->group(function () {
                Route::get('/', [UserProfileController::class, 'carriedStockPage']);
                Route::get('/data', [UserProfileController::class, 'getCarriedStock']);
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
        Route::prefix('stocks')->group(function () {
            Route::get('/', [StockController::class, 'index']);
            Route::get('/data', [StockController::class, 'data']);
            Route::get('/search', [StockController::class, 'goodsSearch']);
            Route::get('/filter', [StockController::class, 'goodsFilter']);
            Route::get('/show/{itemCollection}', [StockController::class, 'show']);
            Route::get('/detail/{stock}', [StockController::class, 'detail']);
            Route::get('/branch/data/{itemCollection}', [StockController::class, 'getMainBranchWithStock']);
            Route::get('/stock-based-on-draft-stock/{draftStock}', [StockController::class, 'findByDraftStockAndItemName']);
            Route::get('/must-reorder', [StockController::class, 'getMustReorderStocks']);
            Route::get('/{branch}/{itemCollection}', [StockController::class, 'findByItemAndBranch']);
        });
        Route::prefix('/stock-withdrawals')->group(function () {
            Route::get('/', [StockWithdrawalController::class, 'index']);
            Route::get('/data', [StockWithdrawalController::class, 'data']);
            Route::get('/search', [StockWithdrawalController::class, 'search']);
            Route::get('/filter', [StockWithdrawalController::class, 'filter']);
            Route::get('/create', [StockWithdrawalController::class, 'create']);
            Route::post('/store', [StockWithdrawalController::class, 'store']);
            Route::get('/edit/{stockWithdrawal}', [StockWithdrawalController::class, 'edit']);
            Route::get('/show/{stockWithdrawal}', [StockWithdrawalController::class, 'show']);
            Route::delete('/destroy/{stockWithdrawal}', [StockWithdrawalController::class, 'destroy']);
            Route::post('/confirmed-by-pic/{stockWithdrawal}', [StockWithdrawalController::class, 'confirmedByPIC']);
            Route::post('/confirmed-by-stocker/{stockWithdrawal}', [StockWithdrawalController::class, 'confirmedByStocker']);
            Route::get('/return/{stockWithdrawal}', [StockWithdrawalController::class, 'return']);
            Route::get('/stock-withdrawal-items/{stockWithdrawal}', [StockWithdrawalController::class, 'getStockWithdrawalItems']);
            Route::get('/stock-withdrawal-item/{stockWithdrawalItem}', [StockWithdrawalController::class, 'getStockWithdrawalItem']);
            Route::post('/stock-withdrawal-item/return/{stockWithdrawalItem}', [StockWithdrawalController::class, 'returningItems']);
        });
        Route::prefix('/stock-withdrawal-items')->group(function () {
            Route::get('/', [StockWithdrawalItemController::class, 'index']);
            Route::get('/data', [StockWithdrawalItemController::class, 'data']);
            Route::get('/filter', [StockWithdrawalItemController::class, 'filter']);
            Route::get('/search', [StockWithdrawalItemController::class, 'search']);
            Route::get('/count', [StockWithdrawalItemController::class, 'getCount']);
        });


        Route::prefix('/stock-mutations')->group(function () {
            Route::get('/', [StockMutationController::class, 'index']);
            Route::get('/data', [StockMutationController::class, 'data']);
            Route::get('/create', [StockMutationController::class, 'create']);
            Route::post('/store', [StockMutationController::class, 'store']);
            Route::post('/destroy', [StockMutationController::class, 'destroy']);
            Route::get('/show/{stockMutation}', [StockMutationController::class, 'show']);
            Route::post('/send-item/{stockMutation}', [StockMutationController::class, 'sendItem']);
            Route::post('/cancel-delivery/{stockMutation}', [StockMutationController::class, 'cancelDelivery']);
            Route::get('/bast-document/{stockMutation}', [StockMutationController::class, 'bastDocument']);
        });
        Route::prefix('draft-stocks')->group(function () {
            Route::get('/', [DraftStockController::class, 'index']);
            Route::get('/data', [DraftStockController::class, 'data']);
            Route::get('/search', [DraftStockController::class, 'search']);
            Route::post('/', [DraftStockController::class, 'store']);
            Route::get('/filter', [DraftStockController::class, 'filter']);
            Route::get('/get-qty', [DraftStockController::class, 'getQty']);
            Route::get('/show/{draftStock}', [DraftStockController::class, 'show']);

            Route::prefix('detail')->group(function () {
                Route::get('/{draftStock}', [DraftStockController::class, 'detail']);
                Route::get('/item-catalog/data/{draftStock}', [ItemCatalogController::class, 'findByDraftStock']);
                Route::post('/item-catalog/save/{draftStock}', [ItemCatalogController::class, 'store']);
            });
        });
        Route::prefix('item-catalog')->group(function () {
            Route::get('/generate-code/{draftStock}', [ItemCatalogController::class, 'generateAutomaticItemCode']);
            Route::get('/{itemCatalog}', [ItemCatalogController::class, 'edit']);
            Route::post('/destroy/{itemCatalog}', [ItemCatalogController::class, 'destroy']);
        });


        Route::prefix('/must-reorder-stocks')->group(function () {
            Route::get('/', [MustReorderStockController::class, 'index']);
            Route::get('/data', [MustReorderStockController::class, 'data']);
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
            Route::get('/', [OfferingLetterController::class, 'index']);
            Route::get('/data', [OfferingLetterController::class, 'data']);
            Route::get('/branch/data', [OfferingLetterController::class, 'branchData']);
            Route::get('filter/branch/data/{branch}', [OfferingLetterController::class, 'filterByBranch']);
            Route::get('/search', [OfferingLetterController::class, 'search']);
            Route::get('/create', [OfferingLetterController::class, 'create']);
            Route::get('/contact/data', [OfferingLetterController::class, 'getContactData']);
            Route::get('/service-categories/data', [OfferingLetterController::class, 'getServicesCategoriesData']);
            Route::get('/unit-types/data', [OfferingLetterController::class, 'getUnitType']);
            Route::get('/unit-types/selected/{offeringLetterProduct}', [OfferingLetterController::class, 'getSelectedUnitType']);
            Route::get('/skl/data', [OfferingLetterController::class, 'getSKL']);
            Route::get('/skl/selected/{offeringLetterServiceDescription}', [OfferingLetterController::class, 'getSelectedSKL']);
            Route::post('/', [OfferingLetterController::class, 'store']);
            Route::get('/users/data', [OfferingLetterController::class, 'getUserData']);
            Route::get('/users/selected/{offeringLetter}', [OfferingLetterController::class, 'selectedUser']);
            Route::post('/update/{offeringLetter}', [OfferingLetterController::class, 'update']);
            Route::get('/view-file/{offeringLetter}', [OfferingLetterController::class, 'viewFile']);
            Route::get('/detail/{offeringLetter}', [OfferingLetterController::class, 'show']);
            Route::get('/edit/{offeringLetter}', [OfferingLetterController::class, 'edit']);
            Route::get(
                '/get-selected-products/{offeringLetter}',
                [OfferingLetterController::class, 'getOfferingLettersProduct']
            );
            Route::get(
                '/get-selected-offering-letters-service-description/{offeringLetter}',
                [OfferingLetterController::class, 'getOfferingLetterDescription']
            );
            Route::get(
                '/get-selected-contact/{offeringLetter}',
                [OfferingLetterController::class, 'getSelectedContact']
            );
            Route::get(
                '/get-selected-services/{offeringLetter}',
                [OfferingLetterController::class, 'getOfferingLetterProductServices']
            );
            Route::post('/confirm/{offeringLetter}', [OfferingLetterController::class, 'confirm']);
            Route::delete('/{offeringLetter}', [OfferingLetterController::class, 'destroy']);
            Route::get('/export-pdf/{offeringLetter}', [OfferingLetterController::class, 'exportToPDF']);
        });

        Route::prefix('po')->group(function () {
            Route::get('/', [PurchaseOrderController::class, 'index']);
            Route::get('/data', [PurchaseOrderController::class, 'data']);
            Route::get('/search', [PurchaseOrderController::class, 'search']);
            Route::get('/create', [PurchaseOrderController::class, 'create']);
            Route::get('/contacts/data', [PurchaseOrderController::class, 'getContactData']);
            Route::get('/unit-types/data', [PurchaseOrderController::class, 'getUnitTypeData']);
            Route::get('/users/data', [PurchaseOrderController::class, 'getUserData']);
            Route::get('/offering-letter/{contact}', [PurchaseOrderController::class, 'getOfferingLetterIfExists']);
            Route::post('/', [PurchaseOrderController::class, 'store']);
            Route::get('/detail/{purchaseOrder}', [PurchaseOrderController::class, 'detail']);
            Route::post('/confirm/{purchaseOrder}', [PurchaseOrderController::class, 'confirm']);
            Route::get('/export-pdf/{purchaseOrder}', [PurchaseOrderController::class, 'exportToPDF']);
            Route::get('/edit/{purchaseOrder}', [PurchaseOrderController::class, 'edit']);
            Route::get('/pic/selected/{purchaseOrder}', [PurchaseOrderController::class, 'selectedPIC']);
            Route::get('/contact/selected/{purchaseOrder}', [PurchaseOrderController::class, 'selectedContact']);
            Route::get('/purchase-order-item/selected/{purchaseOrder}', [PurchaseOrderController::class, 'getPurchaseOrderItem']);
            Route::post('/update/{purchaseOrder}', [PurchaseOrderController::class, 'update']);
        });

        Route::prefix('fab')->group(function () {
            Route::get('/', [FabController::class, 'index']);
            Route::get('/data', [FabController::class, 'data']);
            Route::get('/search', [FabController::class, 'search']);
            Route::get('/branch/data', [FabController::class, 'branchData']);
            Route::get('/filter/branch/data/{branch}', [FabController::class, 'filterByBranch']);
            Route::get('/offering-letter/{contact}', [FabController::class, 'getOfferingLetterIfExists']);
            Route::get('/create', [FabController::class, 'create']);
            Route::get('/po/data', [FabController::class, 'getPOData']);
            Route::get('/services-categories/data', [FabController::class, 'getServicesCategoriesData']);
            Route::post('/', [FabController::class, 'store']);
            Route::get('/view-file/{fab}', [FabController::class, 'viewFile']);
            Route::get('/detail/{fab}', [FabController::class, 'detail']);
            Route::get('/edit/{fab}', [FabController::class, 'edit']);
            Route::get('/get-selected-po/{fab}', [FabController::class, 'selectedPO']);
            Route::get('/get-selected-services/{fab}', [FabController::class, 'selectedServices']);
            Route::get('/get-selected-skl/{fab}', [FabController::class, 'selectedSKL']);
            Route::post('/update/{fab}', [FabController::class, 'update']);
            Route::post('/confirm/{fab}', [FabController::class, 'confirm']);
            Route::get('/jurnal-entry/{fab}', [FabController::class, 'jurnalEntry']);
            Route::get('/users/data', [FabController::class, 'getUserData']);
            Route::get('/users/selected/{fab}', [FabController::class, 'getSelectedUser']);
            Route::delete('/{fab}', [FabController::class, 'destroy']);
            Route::get('/export-pdf/{fab}', [FabController::class, 'exportPDF']);
            Route::get('/get-skl/data', [FabController::class, 'getSkl']);
            Route::get('/get-unit-type/data', [FabController::class, 'getUnitType']);
            Route::get('/contract-pdf/{fab}', [FabController::class, 'contractPDF']);
        });

        Route::prefix('baa')->group(function () {
            Route::get('/', [BAAController::class, 'index']);
            Route::get('/data', [BAAController::class, 'data']);
            Route::get('/search', [BAAController::class, 'search']);
            Route::get('/create', [BAAController::class, 'create']);
            Route::get('/fab/data', [BAAController::class, 'getFabData']);
            Route::get('/fab/selected/{baa}', [BAAController::class, 'selectedFabData']);
            Route::post('/destroy', [BAAController::class, 'destroy']);
            Route::post('/', [BAAController::class, 'store']);
            Route::get('/detail/{baa}', [BAAController::class, 'detail']);
            Route::post('/update/{baa}', [BAAController::class, 'update']);
            Route::post('/confirm/{baa}', [BAAController::class, 'confirm']);
            Route::get('/edit/{baa}', [BAAController::class, 'edit']);
            Route::get('/export-pdf/{baa}', [BAAController::class, 'exportPDF']);
            Route::get('/spk/{baa}', [BAAController::class, 'spk']);
            Route::delete('destroy/{baa}', [BAAController::class, 'destroy']);
            Route::post('save-spk/{baa}', [BAAController::class, 'saveSpk']);
            Route::get('get-spk/{baa}', [BAAController::class, 'getSpk']);
            Route::get('get-selected-from/{user}', [BAAController::class, 'getSelectedFrom']);
            Route::get('get-selected-to/{user}', [BAAController::class, 'getSelectedTo']);
            Route::get('users/data', [BAAController::class, 'getUserData']);
            Route::get('spk/export-pdf/{baa}', [BAAController::class, 'exportSpkToPDF']);
        });


        Route::prefix('bast')->group(function () {
            Route::get('/', [BastController::class, 'index']);
            Route::get('/data', [BastController::class, 'data']);
            Route::get('/search', [BastController::class, 'search']);
            Route::get('/baa/data', [BastController::class, 'getBAAData']);
            Route::get('/baa/selected/{bast}', [BastController::class, 'selectedBAA']);
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
            Route::get('/search', [NationalHolidayController::class, 'search']);
        });
        Route::prefix('/fp-devices')->group(function () {
            Route::get('/', [FpDevicesController::class, 'index']);
            Route::get('/data', [FpDevicesController::class, 'data']);
            Route::get('/filter', [FpDevicesController::class, 'filter']);
            Route::post('/{fpDevice}', [FpDevicesController::class, 'update']);
            Route::get('/search', [FpDevicesController::class, 'search']);
            Route::post('/', [FpDevicesController::class, 'store']);
            Route::get('/{fpDevice}', [FpDevicesController::class, 'edit']);
            Route::post('/destroy', [FpDevicesController::class, 'destroy']);

            Route::post('/test-connection/{fpDevice}', [FpDevicesController::class, 'testConnection']);
            Route::post('/attendance-log/{fpDevice}', [FpDevicesController::class, 'getAttendances']);

            Route::get('/restart-device/{fpDevice}', [FpDevicesController::class, 'restartDevice']);
            Route::post('/get-users/{fpDevice}', [FpDevicesController::class, 'getUser']);

            Route::get('/job-status/{fpDevice}', [FpDevicesController::class, 'getJobStatus']);
        });

        Route::prefix('/work-time')->group(function () {
            Route::get('/', [WorkTimeController::class, 'index']);
            Route::get('/data', [WorkTimeController::class, 'data']);
            Route::get('/user/data', [WorkTimeController::class, 'getUserData']);
            Route::get('/search', [WorkTimeController::class, 'search']);
            Route::post('/', [WorkTimeController::class, 'store']);
            Route::post('/destroy', [WorkTimeController::class, 'destroy']);
            Route::get('/{workTime}', [WorkTimeController::class, 'edit']);
            Route::post('/{workTime}', [WorkTimeController::class, 'update']);
            Route::post('/detail/data/destroy', [WorkTimeController::class, 'destroyDetailWorktimeUser']);
            Route::get('/user/selected/{workTime}', [WorkTimeController::class, 'getSelectedUserWorkTime']);
            Route::post('/set-global-default-work-time/{workTime}', [WorkTimeController::class, 'setGlobalDefaultWorkTime']);
        });

        Route::prefix('/attendances-summary')->group(function () {
            Route::get('/', [AttendanceSummaryController::class, 'index']);
            Route::get('/data', [AttendanceSummaryController::class, 'data']);
            Route::get('/search', [AttendanceSummaryController::class, 'search']);
            Route::get('/detail/filter/{user}', [AttendanceSummaryController::class, 'filterByDate']);
            Route::get('/filter-date', [AttendanceSummaryController::class, 'filterByDate']);
            Route:: get('/branch/data', [AttendanceSummaryController::class, 'getBranchData']);
            Route::get('/department/data', [AttendanceSummaryController::class, 'getDepartmentData']);
            Route::get('/roles/data', [AttendanceSummaryController::class, 'getRolesData']);
            Route::get('/detail/running-commands/{user}', [AttendanceSummaryController::class, 'getRunningCommands']);
            Route::get('/detail/deactivate-active-commands/{user}', [AttendanceSummaryController::class, 'deactivateRunningCommand']);
            Route::get('/detail/get-fp-devices', [AttendanceSummaryController::class, 'getFPDeviceData']);
            Route::get('/detail/{user}/{startDate?}/{endDate?}', [AttendanceSummaryController::class, 'detail']);
            Route::get('/detail/data/{user}/{startDate?}/{endDate?}', [AttendanceSummaryController::class, 'detailData']);;
            Route::get('/filter', [AttendanceSummaryController::class, 'filter']);

            Route::prefix('/attendance-manual-requests')->group(function () {
                Route::get('/', [AttendanceManualRequestController::class, 'index']);
                Route::post('/destroy', [AttendanceManualRequestController::class, 'destroy']);
                Route::get('/data', [AttendanceManualRequestController::class, 'data']);
                Route::get('/create', [AttendanceManualRequestController::class, 'create']);
                Route::post('/', [AttendanceManualRequestController::class, 'store']);
                Route::get('/search', [AttendanceManualRequestController::class, 'search']);
                Route::get('/attachments/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'getAttachments']);
                Route::get('/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'edit']);
                Route::get('/selected-users/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'selectedUsers']);
                Route::get('/selected-attachments/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'selectedAttachments']);
                Route::post('/remove-attachment/{attendanceManualAttachment}', [AttendanceManualRequestController::class, 'removeAttachment']);
                Route::post('/update/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'update']);
                Route::get('/show/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'show']);
                Route::post('/confirm/{attendanceManualRequest}', [AttendanceManualRequestController::class, 'confirm']);
            });

        });

        Route::prefix('/employee-schedules')->group(function () {
            Route::get('/', [EmployeeScheduleController::class, 'index']);
            Route::get('/data', [EmployeeScheduleController::class, 'data']);
            Route::get('/search', [EmployeeScheduleController::class, 'search']);
            Route::get('/get-schedules/{date}/{employeeId}', [EmployeeScheduleController::class, 'getSchedules']);
            Route::get('/work-time/data', [EmployeeScheduleController::class, 'getWorkTime']);
            Route::get('/work-time/selected/{employeeSchedule}', [EmployeeScheduleController::class, 'selectedWorkTime']);
            Route::post('/', [EmployeeScheduleController::class, 'saveSchedules']);
            Route::get('/filter', [EmployeeScheduleController::class, 'filterByDate']);
            Route::get('/national-holidays', [EmployeeScheduleController::class, 'getNationalHoliday']);
            Route::get('/export-pdf', [EmployeeScheduleController::class, 'exportToPDF']);
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
            Route::get('/filter', [PositionAllowancesController::class, 'filter']);
            Route::get('/data', [PositionAllowancesController::class, 'data']);
            Route::get('/search', [PositionAllowancesController::class, 'search']);
            Route::post('/', [PositionAllowancesController::class, 'store']);
            Route::get('/{user}', [PositionAllowancesController::class, 'show']);
            Route::post('/{user}', [PositionAllowancesController::class, 'updateOrStore']);
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


    Route::prefix('payroll/generate-payroll')->group(function () {
        Route::prefix('/employee')->group(function () {
            Route::get('/', [GeneratePayrollController::class, 'index']);
            Route::get('/attendances-data', [GeneratePayrollController::class, 'data']);
            Route::get('/attendance-summary', [GeneratePayrollController::class, 'getAttendancesSummary']);
            Route::get('/user-job-info', [GeneratePayrollController::class, 'getUserJobInformation']);
        });

        Route::prefix('/vendor')->group(function () {
            Route::get('/', [VendorPayrollController::class, 'index']);
            Route::get('/data', [VendorPayrollController::class, 'data']);
            Route::get('/psb-data', [VendorPayrollController::class, 'psbData']);
        });
    });


    Route::prefix('payroll/payroll-history')->group(function () {
        Route::get('/', [PayrollHistoryController::class, 'index']);
        Route::get('/data', [PayrollHistoryController::class, 'data']);
    });


    Route::prefix('select2')->group(function () {
        Route::get('/main-branches-data', [BranchController::class, 'getMainBranches']);
        Route::get('/sub-branches-data/{branch}', [BranchController::class, 'getSubBranches']);
        Route::get('/selected-branch/{branch}', [BranchController::class, 'selectedBranch']);
        Route::get('/companies-data', [CompanyController::class, 'getCompanies']);
        Route::get('/selected-company/{company}', [CompanyController::class, 'selectedCompany']);
        Route::get('/roles-data', [RoleController::class, 'getRoles']);
        Route::get('/selected-role/{role}', [RoleController::class, 'selectedRole']);
        Route::get('/work-times-data', [WorkTimeController::class, 'getWorkTimes']);
        Route::get('/selected-work-time/{workTime}', [WorkTimeController::class, 'selectedWorkTime']);
        Route::get('/departments-data', [DepartmentController::class, 'getDepartments']);
        Route::get('/selected-department/{department}', [DepartmentController::class, 'selectedDepartment']);
        Route::get('/accounts-data', [AccountController::class, 'getAccounts']);
        Route::get('/selected-account/{account}', [AccountController::class, 'selectedAccount']);
        Route::get('/work-times-data', [WorkTimeController::class, 'getWorkTimes']);
        Route::get('/selected-work-time/{workTime}', [WorkTimeController::class, 'selectedWorkTime']);
        Route::get('/unit-types-data', [UnitTypeController::class, 'getUnitTypes']);
        Route::get('/selected-unit-type/{unitType}', [UnitTypeController::class, 'selectedUnitType']);
        Route::get('/users-data', [UserController::class, 'getUsers']);
        Route::get('/selected-user/{user}', [UserController::class, 'selectedUser']);
        Route::get('/contacts-data', [ContactController::class, 'getContacts']);
        Route::get('/selected-contact/{contact}', [ContactController::class, 'selectedContact']);
        Route::get('/service-categories-data', [ServiceCategoryManagerController::class, 'getServiceCategories']);
        Route::get('/selected-service-category/{serviceCategory}', [ServiceCategoryManagerController::class, 'selectedServiceCategory']);

        Route::get('/skl-data', [SKLController::class, 'getSKL']);
        Route::get('/selected-skl/{skl}', [SKLController::class, 'selectedSKL']);
        Route::get('/purchase-orders-data', [PurchaseOrderController::class, 'getPurchaseOrders']);
        Route::get('/selected-purchase-order/{purchaseOrder}', [PurchaseOrderController::class, 'selectedPurchaseOrder']);
        Route::get('/baa-data', [BAAController::class, 'getBAA']);
        Route::get('/selected-baa/{baa}', [BAAController::class, 'selectedBAA']);
        Route::get('/fab-data', [FabController::class, 'getFab']);
        Route::get('/selected-fab/{fab}', [FabController::class, 'selectedFab']);
        Route::get('/item-categories-data', [ItemCategoryController::class, 'getItemCategories']);
        Route::get('/selected-item-category/{itemCategory}', [ItemCategoryController::class, 'selectedItemCategory']);
        Route::get('/goods-data', [ItemCollectionController::class, 'getGoods']);
        Route::get('/selected-item/{item}', [ItemCollectionController::class, 'selectedItem']);
        Route::get('/asset-accounts-data', [AccountController::class, 'assetAccounts']);
        Route::get('/kas-and-leverages-accounts-data', [AccountController::class, 'kasAndLeverageAccounts']);
        Route::get('/kas-accounts-data', [AccountController::class, 'kasAccounts']);
        Route::get('/stock-accounts-data', [AccountController::class, 'stockAccounts']);
        Route::get('/branches-data', [BranchController::class, 'getAllBranch']);
        Route::get('/stock-with-codes-data', [StockController::class, 'getStockWithCodes']);
        Route::get('/stock-without-codes-data', [StockController::class, 'getStockWithoutCode']);
        Route::get('/user-has-areas-data', [UserController::class, 'getUserHasArea']);
        Route::get('/suppliers-data', [SupplierController::class, 'getSuppliers']);
        Route::get('/selected-supplier/{supplier}', [SupplierController::class, 'selectedSupplier']);
        Route::get('/asset-items-data', [ItemCollectionController::class, 'getAssetData']);
        Route::get('/stocker-by-branch-data', [UserController::class, 'getStockerByBranchId']);

    });


    Route::prefix('account-transactions')->group(function () {
        Route::get('/data/{account}', [AccountTransactionController::class, 'accountTransactionHistory']);
        Route::get('/{account}', [AccountTransactionController::class, 'index']);
        Route::get('/search', [AccountTransactionController::class, 'search']);
    });
});



