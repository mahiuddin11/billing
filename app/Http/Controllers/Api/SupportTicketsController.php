<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SupportCategory;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportTicketsController extends Controller
{
    /**
     * String property
     */
    protected $routeName =  'supportticket';
    protected $viewName =  'admin.pages.supportTicket.supportticket';

    protected function getModel()
    {
        return new SupportTicket();
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
                'label' => 'User Name/IP',
                'data' => 'name',
                'searchable' => false,
            ],
            [
                'label' => 'Customer Name',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'customer',
            ],
            [
                'label' => 'Mobile ',
                'data' => 'phone',
                'searchable' => false,
                'relation' => 'customer',
            ],
            [
                'label' => 'Complain No.',
                'data' => 'complain_number',
                'searchable' => false,
            ],
            [
                'label' => 'Problem',
                'data' => 'name',
                'searchable' => false,
                'relation' => 'problem',
            ],
            [
                'label' => 'Priority',
                'data' => 'priority',
                'searchable' => false,
            ],
            [
                'label' => 'Complain Time',
                'data' => 'complain_time',
                'searchable' => false,
            ],
            [
                'label' => 'Status',
                'data' => 'status',
                'searchable' => false,
            ],
            [
                'label' => 'Assign To',
                'data' => 'name',
                'searchable' => false,
                'relation' => "assignUser",
            ],
            [
                'label' => 'Solved Time',
                'data' => 'name',
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
    public function index($id)
    {
        $tickets = SupportTicket::where('client_id', $id)->get();

        // Prepare the response data
        $responses = [];
        foreach ($tickets as $ticket) {
            $responses[] = [
                'comment' => $ticket->note ?? '',
                'status' => strip_tags($ticket->status) ?? '', // Remove HTML tags
            ];
        }

        // Return JSON response
        return $responses;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dataProcessing(Request $request)
    {
        $dataResponse = $this->getDataResponse(
            $this->getModel(),
            $this->tableColumnNames(),
            $this->routeName
        );

        // Return JSON response
        return response()->json($dataResponse);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Support Ticket Create";
        $page_heading = "Support Ticket Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $customers = Customer::get();
        $supportCategorys = SupportCategory::get();
        $users = User::get();

        // Return JSON response
        return response()->json([
            'page_title' => $page_title,
            'page_heading' => $page_heading,
            'back_url' => $back_url,
            'store_url' => $store_url,
            'customers' => $customers,
            'supportCategorys' => $supportCategorys,
            'users' => $users,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


     public function store(Request $request, $id)
     {
         try {
             // Validate the request data
             $validated = $request->validate([
                 'note' => ['required'],

             ]);

             $packageUpdateDownRate = new SupportTicket($validated);

             $packageUpdateDownRate->client_id = $id;

             $packageUpdateDownRate->note = $request->note;



             $packageUpdateDownRate->save();
             $responseData = [
                 'message' => 'Ticket Submitted successfully',
                //  'comment' => $packageUpdateDownRate->note,

             ];

             return response()->json($responseData);
         } catch (\Exception $e) {
             return response()->json(['error' => 'Failed to store data', 'message' => $e->getMessage()]);
         }
     }

        // Other methods...


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(SupportTicket $supportticket)
    {
        $page_title = "Support Ticket Edit";
        $page_heading = "Support Ticket Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $supportticket->id);
        $editinfo = $supportticket;
        $customers = Customer::get();
        $supportCategorys = SupportCategory::get();
        $users = User::get();

        // Return JSON response
        return response()->json([
            'page_title' => $page_title,
            'page_heading' => $page_heading,
            'back_url' => $back_url,
            'update_url' => $update_url,
            'editinfo' => $editinfo,
            'customers' => $customers,
            'supportCategorys' => $supportCategorys,
            'users' => $users,
        ]);
    }

        // Existing methods...

        public function update(Request $request, SupportTicket $supportticket)
        {
            $validated = $this->validate($request, [
                'client_id' => ['required'],
                'priority' => ['required'],
                'assign_to' => ['required'],
                'problem_category' => ['nullable'],
                'complain_number' => ['nullable'],
                'note' => ['nullable'],
            ]);

            try {
                DB::beginTransaction();

                $validated['complain_time'] = now();
                $validated['updated_by'] = auth()->id();
                $supportticket->update($validated);

                DB::commit();

                // Return JSON response
                return response()->json([
                    'success' => true,
                    'message' => 'Data Updated Successfully',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                // Return JSON response for failure
                return response()->json([
                    'success' => false,
                    'message' => 'Oops! Something went wrong.',
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ]);
            }
        }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(SupportTicket $supportticket)
    {
        try {
            $supportticket->delete();

            // Return JSON response for success
            return response()->json([
                'success' => true,
                'message' => 'Data deleted successfully.',
            ]);
        } catch (\Exception $e) {
            // Return JSON response for failure
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function userDetails(Request $request)
    {
        try {
            $userDetail = Customer::find($request->userid);

            // Return JSON response for success
            return response()->json([
                'success' => true,
                'userDetail' => $userDetail,
            ]);
        } catch (\Exception $e) {
            // Return JSON response for failure
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }

    public function status(SupportTicket $supportticket)
    {
        $page_title = "Support Ticket Status";
        $page_heading = "Support Ticket Status";
        $back_url = route($this->routeName . '.index');
        $statusupdate = $supportticket;

        // Return JSON response
        return response()->json([
            'page_title' => $page_title,
            'page_heading' => $page_heading,
            'back_url' => $back_url,
            'statusupdate' => $statusupdate,
        ]);
    }


    public function statusupdate(SupportTicket $supportticket)
    {
        try {
            DB::beginTransaction();
            $validated['status'] = 'Solved';
            $supportticket->update($validated);
            DB::commit();

            // Return JSON response for success
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            // Return JSON response for failure
            return response()->json([
                'success' => false,
                'message' => 'Oops! Something went wrong.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
        }
    }
}
