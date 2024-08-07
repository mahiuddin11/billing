<?php

namespace App\Helpers\DataProcessingFile;

use App\Models\Customer;
use \RouterOS\Query;

trait MikrotikDataProcessing
{

    protected function reformatForRelationalColumnName(array $columnsName)
    {
        $columns = [];
        foreach ($columnsName as $columnName) {
            if (array_key_exists('data', $columnName)) {
                $columns[] = $columnName;
            }
        }
        return $columns;
    }
    /**
     * This method is for proces and return html table row
     * @var $modal instance
     * @var $tableColumnsName
     * @var $routeName
     * @var $show_action
     * @var $action as array
     * @return string
     */
    public function getMikrotikDataResponse($tableColumnsName, $id)
    {
        $client = $this->client(request('columns.0.search.value'));

        if ($id == 3) {
            if (empty(request('search.value'))) {
                $users = $client->query('/ppp/secret/print')->read();
            } else {
                $users = $client->query('/ppp/secret/print', ['name', request('search.value')])->read();
            }
            $data = array();
            if ($users) {
                foreach ($users as $key => $item) {

                    foreach ($tableColumnsName as $columnItem) {
                        $columnName = $columnItem['data'];
                        $nestedData[$columnItem['data']] = $item[$columnName] ?? 'N/A';
                        $customer = Customer::where('mid', $item['.id'])->where('server_id', request('columns.0.search.value'))->first();
                        // ' . $disabled_or_not ? "disabled=''" : "" . '
                        if ($columnItem['data'] == 'check') {
                            if ($customer && ($customer->server_id == request('columns.0.search.value') || empty($customer->server_id))) {
                                $nestedData[$columnItem['data']] = '<div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]"  disabled class="custom-control-input" value="' . $item['.id'] . '"  id="Check' . $key . '" >
                                            <label class="custom-control-label" for="Check' . $key . '"></label>
                                        </div>';
                            } else {
                                $nestedData[$columnItem['data']] = '<div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]"  class="custom-control-input" value="' . $item['.id'] . '"  id="Check' . $key . '" >
                                            <label class="custom-control-label" for="Check' . $key . '"></label>
                                        </div>';
                            }
                        }

                        if ($columnItem['data'] == 'disabled') {
                            $nestedData[$columnItem['data']] = $item[$columnName] == 'false' ? "<i class='text-success fas fa-circle'></i>" : "<i class='fas fa-circle'></i>";
                        }

                        if ($columnItem['data'] == 'company_name') {
                            $nestedData[$columnItem['data']] = $customer->getCompany->company_name ?? "N/A";
                        }
                    }

                    $data[] = $nestedData;
                }
            }
        } elseif ($id == 1) {

            if (empty(request('search.value'))) {
                $users = $client->query('/queue/simple/print')->read();
            } else {
                $users = $client->query('/queue/simple/print', ['name', request('search.value')])->read();
            }
            $data = array();
            if ($users) {
                foreach ($users as $key => $item) {

                    foreach ($tableColumnsName as $columnItem) {
                        $columnName = $columnItem['data'];
                        $nestedData[$columnItem['data']] = $item[$columnName] ?? 'N/A';
                        $customer = Customer::where('queue_id', $item['.id'])->where('server_id', request('columns.0.search.value'))->first();
                        // ' . $disabled_or_not ? "disabled=''" : "" . '
                        if ($columnItem['data'] == 'check') {
                            if ($customer && ($customer->server_id == request('columns.0.search.value') || empty($customer->server_id))) {
                                $nestedData[$columnItem['data']] = '<div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]"  disabled class="custom-control-input" value="' . $item['.id'] . '"  id="Check' . $key . '" >
                                            <label class="custom-control-label" for="Check' . $key . '"></label>
                                        </div>';
                            } else {
                                $nestedData[$columnItem['data']] = '<div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="customers[]"  class="custom-control-input" value="' . $item['.id'] . '"  id="Check' . $key . '" >
                                            <label class="custom-control-label" for="Check' . $key . '"></label>
                                        </div>';
                            }
                        }

                        if ($columnItem['data'] == 'disabled') {
                            $nestedData[$columnItem['data']] = $item[$columnName] == 'false' ? "<i class='text-success fas fa-circle'></i>" : "<i class='fas fa-circle'></i>";
                        }

                        if ($columnItem['data'] == 'company_name') {
                            $nestedData[$columnItem['data']] = $customer->getCompany->company_name ?? "N/A";
                        }
                    }

                    $data[] = $nestedData;
                }
            }
        }

function utf8ize($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = utf8ize($value);
        }
    } else if (is_string($data)) {
        return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
    }
    return $data;
}

$data = utf8ize($data);

$json_data = array(
    "draw" => intval(request('draw')),
    "recordsTotal" => intval(count($users)),
    "recordsFiltered" => intval(count($users)),
    "data" => $data
);

return response()->json($json_data);
    }
}
