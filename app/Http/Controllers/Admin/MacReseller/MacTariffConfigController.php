<?php

namespace App\Http\Controllers\Admin\MacReseller;

use App\Http\Controllers\Controller;
use App\Models\Package2;
use App\Models\MacPackage;
use App\Models\MacTariffConfig;
use App\Models\MikrotikServer;
use App\Models\MPPPProfile;
use App\Models\MProfile;
use App\Models\Protocol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MacTariffConfigController extends Controller
{

    /**
     * String property
     */

    protected $routeName =  'mactariffconfig';
    protected $viewName =  'admin.pages.mactariffconfig';

    protected function getModel()
    {
        return new MacTariffConfig();
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
                'label' => 'ID',
                'data' => 'id',
                'searchable' => false,
            ],
            [
                'label' => 'Tariff Name',
                'data' => 'tariff_name',
                'searchable' => false,
            ],
            [
                'label' => 'Package name',
                'data' => 'package_id',
                'searchable' => false,
            ],
            [
                'label' => 'Package Rate',
                'data' => 'package_rate',
                'searchable' => false,
            ],
            // [
            //     'label' => 'Package Validation Day',
            //     'data' => 'package_validation_day',
            //     'searchable' => false,
            // ],
            // [
            //     'label' => 'Package Minimum Activation Day',
            //     'data' => 'package_minimum_activation_day',
            //     'searchable' => false,
            // ],
            [
                'label' => 'Server Name',
                'data' => 'user_name',
                'searchable' => false,
                'relation' => 'mikrotikserver'
            ],
            // [
            //     'label' => 'Protocol Type',
            //     'data' => 'protocole_type',
            //     'searchable' => false,
            // ],
            // [
            //     'label' => 'PPP Profile',
            //     'data' => 'ppp_profile',
            //     'searchable' => false,
            // ],
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

    public function index()
    {
        $page_title = "Tariff Config";
        $page_heading = "Tariff Config List";
        $ajax_url = route($this->routeName . '.dataProcessing');
        $create_url = route($this->routeName . '.create');
        $is_show_checkbox = false;
        $columns = $this->reformatForRelationalColumnName(
            $this->tableColumnNames()
        );
        return view('admin.pages.index', get_defined_vars());
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

    public function getprofile(Request $request)
    {
        $getModel = $request->modelname::where($request->column, $request->id)->get();

        $data = null;
        foreach ($getModel as $value) :
            $data .= "<option value='" . $value->id . "'>" . $value->name . "</option>";
        endforeach;
        return $data;
    }

    public function getQueue(Request $request)
    {
        $getModel = $request->modelname::where($request->column, $request->id)->get();

        $data = null;
        foreach ($getModel as $value) :
            $data .= "<option value='" . $value->id . "'>" . $value->queue_name . "</option>";
        endforeach;
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = "Tariff Config Create";
        $page_heading = "Tariff Config Create";
        $back_url = route($this->routeName . '.index');
        $store_url = route($this->routeName . '.store');
        $servers = MikrotikServer::where('status', true)->get();
        $macpackages = MacPackage::get();
        $protocols = Protocol::where('status', 'active')->get();
        $mpppprofiles = MPPPProfile::get();
        return view($this->viewName . '.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $valideted = $this->validate($request, [
            'tariff_name' => ['required'],
            'package_id' => ['required'],
            'rate' => ['required'],
            // 'validation_day' => ['required'],
            // 'minimum_activation_day' => ['required', 'min:1'],
            'server_id' => ['required'],
            'protocole_type' => ['required'],
            'm_profile_id' => ['required']
        ]);
        try {
            DB::beginTransaction();
            $tariffconfig['tariff_name'] = $request->tariff_name;
            $tariffconfig['server_id'] = implode(',', $request->server_id);
            $tariffconfig['package_rate'] = implode(',', $request->rate);
            // $tariffconfig['package_validation_day'] = implode(',', $request->validation_day);
            // $tariffconfig['package_minimum_activation_day'] = implode(',', $request->minimum_activation_day);
            $tariffconfig['package_id'] = implode(',', $request->package_name);
            $tariffconfig['protocole_type'] = implode(',', $request->protocole_type);
            $tariffconfig['created_by'] = auth()->id();
            $mactariffconfig =  MacTariffConfig::create($tariffconfig);

            for ($j = 0; $j < count($request->package_id); $j++) {
                $data[] = [
                    'name' => $request->package_name[$j],
                    'tariffconfig_id' => $mactariffconfig->id,
                    'mac_package_id' => $request->package_id[$j],
                    'server_id' => $request->server_id[$j],
                    'protocol_id' => $request->protocole_type[$j],
                    ($request->protocole_type[$j] == 1 ? "m_static_id" : ($request->protocole_type[$j] == 3 ? 'm_profile_id' : "")) => $request->m_profile_id[$j],
                    'rate' => $request->rate[$j],
                    'company_id' => auth()->user()->company_id,
                ];
            }

            Package2::insert($data);

            DB::commit();
            return back()->with('success', 'Data Store Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */

    public function show(Request $request, MacTariffConfig $mactariffconfig)
    {
        $modal_title = 'Tariff Config Details';
        $modal_data = $mactariffconfig;
        $html = view($this->viewName . '.show', get_defined_vars())->render();
        return $html;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */

    public function edit(MacTariffConfig $mactariffconfig)
    {
        $page_title = "Tariff Config Edit";
        $page_heading = "Tariff Config Edit";
        $back_url = route($this->routeName . '.index');
        $update_url = route($this->routeName . '.update', $mactariffconfig->id);
        $editinfo = $mactariffconfig;
        $servers = MikrotikServer::where('status', true)->get();
        $macpackages = MacPackage::get();
        $protocols = Protocol::where('status', 'active')->get();
        $mpppprofiles = MPPPProfile::get();
        return view($this->viewName . '.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MacTariffConfig $mactariffconfig)
    {
        $valideted = $this->validate($request, [
            'tariff_name' => ['required'],
            'package_id' => ['required'],
            'rate' => ['required'],
            // 'validation_day' => ['required'],
            // 'minimum_activation_day' => ['required', 'min:1'],
            'server_id' => ['required'],
            'protocole_type' => ['required'],
            'm_profile_id' => ['required']
        ]);

        try {
            DB::beginTransaction();

            $tariffconfig['tariff_name'] = $request->tariff_name;
            $tariffconfig['server_id'] = implode(',', $request->server_id);
            $tariffconfig['package_rate'] = implode(',', $request->rate);
            // $tariffconfig['package_validation_day'] = implode(',', $request->validation_day);
            // $tariffconfig['package_minimum_activation_day'] = implode(',', $request->minimum_activation_day);
            $tariffconfig['package_id'] = implode(',', $request->package_name);
            $tariffconfig['protocole_type'] = implode(',', $request->protocole_type);
            $tariffconfig['created_by'] = auth()->id();
            $mactariffconfig->update($tariffconfig);
            Package2::where('tariffconfig_id', $mactariffconfig->id)->delete();

            for ($j = 0; $j < count($request->package_id); $j++) {
                $data[] = [
                    'name' => $request->package_name[$j],
                    'tariffconfig_id' => $mactariffconfig->id,
                    'mac_package_id' => $request->package_id[$j],
                    'server_id' => $request->server_id[$j],
                    'protocol_id' => $request->protocole_type[$j],
                    ($request->protocole_type[$j] == 1 ? "m_static_id" : ($request->protocole_type[$j] == 3 ? 'm_profile_id' : "")) => $request->m_profile_id[$j],
                    'rate' => $request->rate[$j],
                    'company_id' => auth()->user()->company_id,
                ];
            }

            Package2::insert($data);

            DB::commit();
            return back()->with('success', 'Data Updated Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('failed', 'Oops! Something was wrong. Message: ' . $e->getMessage() . ' Line: ' . $e->getLine() . 'File: ' . $e->getFile());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(MacTariffConfig $mactariffconfig)
    {
        $mactariffconfig->delete();
        return back()->with('success', 'Data deleted successfully.');
    }
    public function sendMessage(MacTariffConfig $mactariffconfig)
    {
        $editinfo = $mactariffconfig;
        return view('admin.pages.sms.send-message', get_defined_vars());
    }

    public function tarifPackageEdit(Package2 $package2)
    {
        $page_title = "Tariff Config Edit";
        $page_heading = "Tariff Config Edit";
        $servers = MikrotikServer::where('status', true)->get();
        $macpackages = MacPackage::get();
        $editinfo = $package2;
        $protocols = Protocol::where('status', 'active')->get();
        $mpppprofiles = MPPPProfile::get();
        return view('admin.pages.mactariffconfig.tarifpacakageedit', get_defined_vars());
    }

    public function tarifPackageUpdate(Request $request, Package2 $package2)
    {

        $valideted = $this->validate($request, [
            'name' => ['required'],
            'protocol_id' => ['required'],
            'mac_package_id' => ['required'],
            'rate' => ['required'],
            'server_id' => ['required'],
            'm_profile_id' => ['required']
        ]);
        $package2->update($valideted);
        return back()->with('success', 'Data Updated successfully');
    }
}
