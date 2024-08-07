<?php

namespace App\Helpers\DataProcessingFile;

use Carbon\Carbon;

trait customerBillingDataProcessing
{

    protected function reformatForRelationalColumnName(array $columnsName)
    {
        $columns = [];
        foreach ($columnsName as $columnName) {
            if (array_key_exists('data', $columnName)) {
                if (array_key_exists('relation', $columnName)) {
                    $columns[] = [
                        'label' => $columnName['label'],
                        'data' => $columnName['relation'] . '_' . $columnName['data'],
                        'searchable' => $columnName['searchable'],
                        'relation' => $columnName['relation'],
                    ];
                } else {
                    $columns[] = $columnName;
                }
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
    public function getDataResponse($model, $tableColumnsName, $routeName, $show_action = true, $actions = ['edit',  'destroy'])
    {
        /**
         * Sortable column list mgenerate
         */
        $sortableColumns = [];
        foreach ($tableColumnsName as $columnName) {
            if (array_key_exists('data', $columnName)) {
                if (isset($columnName['orderable']) && $columnName['orderable'] == true) {
                    $sortableColumns[] = $columnName['data'];
                } else {
                    $sortableColumns[] = $columnName['data'];
                }
            }
        }

        // end
        // dd(request(), request('columns.4.search.value'));
        $totalData = $model->count();
        $limit = request('length');
        $start = request('start');
        $order = $sortableColumns[request('order.0.column')];
        $dir = request('order.0.dir');
        if (empty(request('search.value'))) {
            $results = $model->offset($start)
                ->limit($limit)
                ->with('getCustomer')
                ->orderBy($order, $dir);
            foreach ($tableColumnsName as $key => $tableColumns) {
                if (array_key_exists('customesearch', $tableColumns) && request('columns.' . $key . '.search.value')) {
                    $value = trim(request('columns.' . $key . '.search.value'), '"');
                    $results =  $results->where($tableColumns['customesearch'],  $value . "%");
                }
                if (array_key_exists('customesearch', $tableColumns) && request('columns.' . 6 . '.search.value')) {
                    $value = trim(request('columns.' . 6 . '.search.value'), '"');
                    $results =  $results->whereDate('exp_date', $value);
                }
            }
            $results = $results->get();
            $totalFiltered = $totalData;
        } else {
            $results = $model->where(function ($query) {
                $search = request('search.value');
                $query->where('customers.name', 'like', "%{$search}%")
                    ->orWhere('customers.username', 'like', "%{$search}%")
                    ->orWhere('customers.queue_name', 'like', "%{$search}%")
                    ->orWhere('customers.phone', 'like', "%{$search}%");
            });


            $results =  $results->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = $results->count();
            // foreach ($tableColumnsName as $tableColumns) {
            //     if ($tableColumns['searchable'] && array_key_exists('data', $tableColumns)) {
            //         $totalFiltered =  $totalFiltered->orWhere($tableColumns['data'], 'like', "%{$search}%");
            //     }
            // }
        }
        $data = array();
        // dd($sortableColumns);
        if ($results) {
            foreach ($results as $key => $item) {
                foreach ($tableColumnsName as $columnItem) {
                    if (array_key_exists('data', $columnItem)) {
                        $columnName = $columnItem['data'];

                        $nestedData[$columnItem['data']] = $item->$columnName ?? null;

                        if (isset($columnItem['relation']) && !empty($columnItem['relation'])) {
                            $relation = $columnItem['relation'];
                            $relatedData = $item->$relation;
                            $nestedData[$columnItem['relation'] . '_' . $columnItem['data']] = $relatedData->$columnName ?? "N/A";
                        }

                        if ($columnItem['data'] == 'date_') {
                            $nestedData['date_'] = Carbon::parse($nestedData['date_'])->format('M-Y');
                        }

                        // if ($columnItem['data'] == 'username') {
                        //     $nestedData['username'] = $item->username ?? $item->queue_name;
                        // }

                        // if (isset($nestedData['getCustomer_exp_date']) && $nestedData['getCustomer_exp_date'] != "N/A") {
                        //     $nestedData['getCustomer_exp_date'] = Carbon::parse($nestedData['getCustomer_exp_date'])->format('d-m-Y');
                        // }

                        if ($columnItem['data'] == 'id') {
                            $nestedData['id'] = $key + 1;
                        }

                        if ($columnItem['data'] == 'pay_amount') {
                            $nestedData['pay_amount'] = $nestedData['pay_amount'] ?? 0;
                        }
                        // if (array_key_exists('checked', $columnItem)) {
                        //     $nestedData[$columnItem['data']] = $this->checkedBtn($item->id, $routeName, $columnItem['data'], $item->$columnName, $columnItem['checked']);
                        // }

                        if ($columnItem['data'] == 'action' && $show_action) {

                            $nestedData['action'] = '';

                            foreach ($actions as $action) {
                                if (!empty($action) && !is_array($action)) {
                                    if (strtolower($action) == 'show') {
                                        $nestedData['action'] .= $this->showBtn(route($routeName . '.show', $item->id));
                                    }
                                } else if (is_array($action)) {
                                if ($item->status != "paid"){
                                    $nestedData['action'] .= $this->customBtn(
                                        route($routeName . '.' . $action['method_name'], $item->id),
                                        $action['class'],
                                        $action['fontawesome'],
                                        $action['text'],
                                        $action['title'],
                                        $action['code'] ?? "",
                                    );
                                 }
                                }
                            }
                        }
                    }
                }

                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval(request('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return $json_data;
    }
}
