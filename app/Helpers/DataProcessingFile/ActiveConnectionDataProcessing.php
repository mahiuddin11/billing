<?php

namespace App\Helpers\DataProcessingFile;

use App\Models\Customer;

trait ActiveConnectionDataProcessing
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
    public function getActiveTable($tableColumnsName)
    {
        $client = $this->client(request('columns.0.search.value'));

        if (empty(request('search.value'))) {
            $activeUsers = $client->query('/ppp/active/print')->read();
        } else {
            $activeUsers = $client->query('/ppp/active/print', ['name', request('search.value')])->read();
        }
        $data = array();
        if ($activeUsers) {
            foreach ($activeUsers as $key => $item) {
                $company = Customer::where('company_id', auth()->user()->company_id)->where('username', $item['name'])->first();
                if ($company) {
                    foreach ($tableColumnsName as $columnItem) {
                        $columnName = $columnItem['data'];
                        $nestedData[$columnItem['data']] = $item[$columnName] ?? 'N/A';
                    }
                    $data[] = $nestedData;
                }
            }
        }
        $json_data = array(
            "draw" => intval(request('draw')),
            "recordsTotal" => intval(count($data)),
            "recordsFiltered" => intval(count($data)),
            "data" => $data
        );
        return $json_data;
    }
}
