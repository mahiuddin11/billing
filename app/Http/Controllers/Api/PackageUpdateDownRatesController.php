<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Package2;
use App\Models\PackageUpdateDownRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PackageUpdateDownRatesController extends Controller
{
    protected $routeName =  'package_update_and_down_rate';
    protected $viewName =  'admin.pages.package_update_and_down_rate';

    protected function getModel()
    {
        return new PackageUpdateDownRate();
    }

    protected function tableColumnNames()
    {
        return [
            // [
            //     'label' => 'Show in Table header',
            //     'data' => 'action',
            //     'class' => 'text-nowrap', class name
            //     'orderable' => false,
            //     'searchable' => false,
            // ],
            [
                'label' => 'Sl',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Customer Name',
                'data' => 'username',
                'searchable' => false,
                'relation' => 'customer'
            ],

            [
                'label' => 'Package Name',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'package'
            ],
            [
                'label' => 'Date',
                'data' => 'date',
                'searchable' => false,
            ],
            [
                'label' => 'Action',
                'data' => 'action',
                'class' => 'text-nowrap',
                'orderable' => false,
                'searchable' => false,
            ],
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   function index($id) {
    $users = PackageUpdateDownRate::where('customer_id', $id)
        ->where('status', 0)
        ->get();
    
    $response = []; 
    
    foreach ($users as $user) {
        $response = [ 
            'customer_name' => $user->customer->name ?? '',
            'package_name' => $user->package->name ?? '',
            'date' => $user->date ?? '',
            'comment' => $user->comment ?? '',
            'status' => $user->status ?? '', 
        ];
    }
    
    return response()->json($response); // Changed from $responses to $response
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing(Request $request)
    {
        return $this->getDataResponse(
            //Model Instance
            $this->getModel(),
            //Table Columns Name
            $this->tableColumnNames(),
            //Route name
            $this->routeName,

        );
    }

    public function create()
    {
        $page_title = "Package Update and Down Rate Create";
        $page_heading = "Package Update and Down Rate Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = Customer::get();
        $packages = Package2::get();

        $data = compact('page_title', 'page_heading', 'back_url', 'store_url', 'customers', 'packages');

        return response()->json($data);
    }

    public function store(Request $request, $id)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'package_id' => ['required'],
                'date' => ['required'],
            ]);
            
            $alreadyExists = PackageUpdateDownRate::where('customer_id', $id)
            ->where('status', 0)
            ->exists();

            if($alreadyExists)
            {
                $responseData = [
                    'message' => 'Your already send a request',
                    ];
            }
            else
            {
                
            
            
                $packageUpdateDownRate = new PackageUpdateDownRate($validated);

                $packageUpdateDownRate->customer_id = $id;

                $packageUpdateDownRate->package_id = $request->package_id;

                $packageUpdateDownRate->date = $request->date;
                $packageUpdateDownRate->comment = $request->comment;

                $packageUpdateDownRate->save();
                $responseData = [
                'message' => 'Request Send Successfully',
                // 'data' => [
                //     'customer_id' => $packageUpdateDownRate->customer_id,
                //     'package_id' => $packageUpdateDownRate->package_id,
                //     'date' => $packageUpdateDownRate->date,
                // ]
            ];

            }

            return response()->json($responseData);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to store data', 'message' => $e->getMessage()]);
        }
    }


    public function edit(PackageUpdateDownRate $Packageupdatedownrate)
    {
        $page_title = "Package Update and Down Rate Edit";
        $page_heading = "Package Update and Down Rate Edit";
        $customers = Customer::get();
        $packages = Package2::get();
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $Packageupdatedownrate->id);
        $editinfo = $Packageupdatedownrate;

        $data = compact('page_title', 'page_heading', 'back_url', 'update_url', 'customers', 'packages', 'editinfo');

        return response()->json($data);
    }
    public function update(Request $request, PackageUpdateDownRate $Packageupdatedownrate)
    {
        try {
            DB::beginTransaction();
            $validated = $request->validate([
                'customer_id' => ['required'],
                'package_id' => ['required'],
                'date' => ['required'],
            ]);

            $Packageupdatedownrate->update($validated);
            DB::commit();

            return response()->json(['message' => 'Data updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update data', 'message' => $e->getMessage()]);
        }
    }
    public function destroy(PackageUpdateDownRate $Packageupdatedownrate)
    {
        try {
            $Packageupdatedownrate->delete();
            return response()->json(['message' => 'Data deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete data', 'message' => $e->getMessage()]);
        }
    }
}
