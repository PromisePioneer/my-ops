<?php

namespace App\Http\Controllers\Allowances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Allowances\MealAllowanceRequest;
use App\Models\User;
use App\Models\UserHasMealAllowance;
use App\Service\UserAllowance\MealAllowanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MealAllowanceController extends Controller
{

    private UserHasMealAllowance $userHasMealAllowance;
    private User $user;
    private MealAllowanceService $mealAllowanceService;

    public function __construct()
    {
        $this->userHasMealAllowance = new UserHasMealAllowance();
        $this->user = new User();
        $this->mealAllowanceService = new MealAllowanceService();
    }


    public function getUser(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function selectedUser(UserHasMealAllowance $userHasMealAllowance): JsonResponse
    {
        return response()->json($this->user->getSelectedData($userHasMealAllowance->user_id));
    }

    public function index(): View
    {
        return view('pages.payroll.allowances.meal.index');
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $query = $this->user->data();


        if (!empty($search)) {
            $query->whereHas('role', function ($query) use ($search) {
                $query->where('name', 'like', "%".$search."%");
            })->where('name', 'like', "%".$search."%")
                ->orWhere('amount', 'like', "%".$search."%");
        }

        $data = $query->paginate(10);
        return response()->json($data);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->userHasMealAllowance->data());
    }

    public function store(MealAllowanceRequest $request): JsonResponse
    {
        $this->mealAllowanceService->store($request);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function edit(UserHasMealAllowance $userHasMealAllowance): JsonResponse
    {
        return response()->json($userHasMealAllowance);
    }


    public function update(MealAllowanceRequest $request, UserHasMealAllowance $userHasMealAllowance): JsonResponse
    {
        $this->mealAllowanceService->update($request, $userHasMealAllowance);
        return response()->json(['message' => 'data berhasil disimpan']);
    }


    public function destroy(
        Request $request,
        UserHasMealAllowance $userHasMealAllowance
    ): JsonResponse {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $userHasMealAllowance->whereIn('id', $explodeID)->delete();


        return response()->json([
            'message' => 'data berhasil dihapus',
        ]);
    }
}
