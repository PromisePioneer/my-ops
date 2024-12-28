<?php

namespace App\Http\Controllers\Inventory\BoQ;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovedByOperationalManagerRequest;
use App\Http\Requests\BoqRequest;
use App\Models\Boq;
use App\Models\BoqCommodity;
use App\Models\BoqTimelineProject;
use App\Models\Item;
use App\Models\UnitType;
use App\Models\User;
use App\Service\BoqService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

#[AllowDynamicProperties] class BoqController extends Controller
{


    public function __construct()
    {
        $this->boqService = new BoqService();
        $this->unitType = new UnitType();
        $this->user = new User();
        $this->item = new Item();
    }

    public function index(): View
    {
        return view('pages.operational.boq.index');
    }

    public function data(Request $request): JsonResponse
    {
        return response()->json($this->boqService->data($request));
    }

    public function search(Request $request): JsonResponse
    {
        return response()->json($this->boqService->search($request));
    }

    public function getUnitTypes(Request $request): JsonResponse
    {
        return response()->json($this->unitType->getData($request));
    }

    public function selectedUnitType($id): JsonResponse
    {
        return response()->json($this->unitType->getSelectedData((int)$id));
    }

    public function getUserData(Request $request): JsonResponse
    {
        return response()->json($this->user->getUser($request));
    }

    public function create(): View
    {
        return view('pages.operational.boq.create');
    }


    /**
     * @throws Throwable
     */
    public function store(BoqRequest $request): JsonResponse
    {
        $this->boqService->store($request);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function getBoqCommodity(Boq $boq): JsonResponse
    {
        $data = BoqCommodity::where('boq_id', $boq->id)->get();
        return response()->json($data);
    }


    public function getProjectTimeline(Boq $boq): JsonResponse
    {
        $data = BoqTimelineProject::where('boq_id', $boq->id)->get();
        return response()->json($data);
    }

    public function selectedUser($id): JsonResponse
    {
        return response()->json($this->user->getSelectedData((int)$id));
    }


    public function edit(Boq $boq): View
    {
        return view('pages.operational.boq.edit', compact('boq'));
    }


    public function update(BoqRequest $request, Boq $boq): JsonResponse
    {
        $this->boqService->update($request, $boq);
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function detail(Boq $boq): View
    {
        $director = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'Director');
        })->first();

        $operationalManager = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'Operational Manager');
        })->first();

        $generalManager = User::with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'General Manager');
        })->first();

        $boqCommodity = BoqCommodity::with('unitType', 'item')->where('boq_id', $boq->id)->get();

//        dd($boqCommodity);

        $boqProjectTimeline = BoqTimelineProject::with('unitType', 'picName')
            ->where('boq_id', $boq->id)
            ->get();


        return view(
            'pages.operational.boq.detail',
            compact('boq', 'boqCommodity', 'director', 'operationalManager', 'generalManager', 'boqProjectTimeline'),
        );
    }

    /**
     * @throws AuthorizationException
     */
    public function approvedByOperationalManager(ApprovedByOperationalManagerRequest $request, Boq $boq): JsonResponse
    {
        $this->authorize('approveBoQ', $boq);
        $boq->operational_manager_approval = $request->operational_manager_approval;
        $boq->reason = $request->reason;
        $boq->operational_manager_id = $request->user()->id;
        $boq->save();
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function knownByDirector(Boq $boq): JsonResponse
    {
        $boq->known_by_director = 1;
        $boq->save();
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function knownByGeneralManager(Boq $boq): JsonResponse
    {
        $boq->known_by_gm = 1;
        $boq->save();
        return response()->json(['message' => 'Data berhasil disimpan.']);
    }


    public function destroy(Request $request, Boq $boq): JsonResponse
    {
        $implodeID = implode(',', $request->get('id'));
        $explodeID = explode(',', $implodeID);
        $boq->whereIn('id', $explodeID)->delete();

        return response()->json([
            'message' => 'data berhasil dihapus',
        ], 200);
    }


    public function getItemData(Request $request): JsonResponse
    {
        return response()->json($this->item->getData($request));
    }

    public function selectedItem(Item $item): JsonResponse
    {
        return response()->json($this->item->getSelectedData($item->id));
    }
}
