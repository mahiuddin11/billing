<?php

namespace App\Imports;

use App\Models\ClientType;
use App\Models\Customer;
use App\Models\MikrotikServer;
use App\Models\Package;
use App\Models\Package2;
use App\Models\Protocol;
use App\Models\Subzone;
use App\Models\Zone;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use \RouterOS\Query;
use App\Helpers\MikrotikConnection;
use App\Models\MPPPProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UsersImport implements ToCollection, WithValidation, SkipsOnError, WithHeadingRow
{
    use MikrotikConnection;

    use Importable, SkipsErrors;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        // dd($rows->toArray());
        try {
            $customerarray =[];

            foreach ($rows as $key => $row) {
                $zone = "";
                // $package = MPPPProfile::where([
                //     'name' => $row['p_p_p_profile']
                // ])->first();

                if ($row['client_type']) {
                    $clienttype = ClientType::firstOrCreate([
                        'name' => $row['client_type']
                    ]);
                }
                if ($row['zone']) {
                    $zone = Zone::firstOrCreate([
                        'name' => $row['zone']
                    ]);
                }

                // $service = Protocol::firstOrCreate([
                //     'name' => $row['protocol']
                // ]);

                // $server = MikrotikServer::first();

                if (!empty($row['subzone'])) {
                    $subzone = Subzone::firstOrCreate([
                        'name' => $row['subzone'],
                        'zone_id' => $zone->id
                    ]);
                }
                $bill_collection_day = $row['bill_collection_day'];
                $startDate =  $valideted['start_date'] = !empty($row['start_date']) ? Carbon::parse($row['start_date'])->format('Y-m-d')  : Carbon::now()->startOfMonth()->format('Y-m-d');
                if ($row['billing_period'] == 'postpaid') {
                    $submonth = 0;
                    $day = $bill_collection_day == 0 ? -1 : $bill_collection_day;
                } else {
                    $submonth = 1;
                    $day =
                        $bill_collection_day;
                }

                // $client = $this->client();
                // $users = $client->query('/ppp/secret/print', ['name', $row['username']])->read();
                $enddate = Carbon::parse($startDate)->addMonths(1)->subMonth($submonth)->day($day)->format('Y-m-d');

                $customer = Customer::where('username', $row['username'])->first();

                if ($customer) {
                    $customerarray[] = [
                        'username' => $row['username'],
                        'name' => $row['full_name'] ?? $row['username'],
                        empty($row['client_id']) ? '' : "client_id" => $row['client_id'],
                        'phone' => $row['phone'],
                        'zone_id' => $zone->id ?? "",
                        'subzone_id' => isset($subzone) ? $subzone->id : null,
                        'address' => $row['address'],
                        'dob' => $row['date_of_birth'] ?? "",
                        'connection_date' => $row['joining_date'] ?? "",
                        'email' => $row['email'],
                        'nid' => $row['nid'],
                        'start_date' => $startDate,
                        'exp_date' => $enddate,
                        'status' => 'active',
                        'auto_line_off' => $row['auto_line_off'] ?? 'yes',
                        'billing_status_id' => $customer->disabled == 'true' ? 4 : 5,
                        'bill_collection_date' => $bill_collection_day,
                        'client_type_id' => $clienttype->id ?? 0,
                        'bill_amount' => $row['bill_amount'],
                        'mac_address' => $row['m_a_c_address'] ?? null,
                    ];
                }
            }
            // dd($customerarray);
            Customer::upsert($customerarray, ['username'], [
                'name',
                'client_id',
                'phone',
                'zone_id',
                'subzone_id',
                'address',
                'server_id',
                'email',
                'dob',
                'connection_date',
                'nid',
                'start_date',
                'exp_date',
                'status',
                'auto_line_off',
                'billing_status_id',
                'bill_collection_date',
                'client_type_id',
                'bill_amount',
                'mac_address',
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('failed', $th->getMessage() . 'line' . $th->getLine() . 'File' . $th->getFile());
        }
    }


    public function rules(): array
    {
        return [
            // 'username' => [
            //     'required', 'unique',
            // ],
        ];
    }

    // public function chunkSize(): int
    // {
    //     return 100;
    // }
}
