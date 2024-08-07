<?php

namespace App\Helpers\DataProcessingFile;

trait MacCustomerBillDataProcessing
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
            $results = $model
                ->selectRaw('MONTH(date_) as month,YEAR(date_) as year,SUM(charge) as charge,company_id')
                ->groupBy('month', 'year', 'company_id')
                ->orderBy('month', 'desc');
            $results = $results->get();
            $totalFiltered = $totalData;
        } else {
            $search = request('search.value');
            $results = $model->where('id', 'like', "%{$search}%");
            $results =  $results
                ->selectRaw('MONTH(date_) as month,YEAR(date_) as year,SUM(charge) as charge,company_id')
                ->groupBy('month', 'year', 'company_id')
                ->orderBy('month', 'desc')
                ->get();
            $totalFiltered = $results->count();
        }
        $data = array();
        if ($results) {
            // dd($results);
            foreach ($results as $key => $item) {
                foreach ($tableColumnsName as $columnItem) {
                    if (array_key_exists('data', $columnItem)) {
                        $columnName = $columnItem['data'];
                        $nestedData[$columnItem['data']] = $item->$columnName ?? 'N/A';
                        if (isset($columnItem['relation']) && !empty($columnItem['relation'])) {
                            $relation = $columnItem['relation'];
                            $relatedData = $item->$relation;
                            $nestedData[$columnItem['relation'] . '_' . $columnItem['data']] = $relatedData->$columnName ?? "N/A";
                        }

                        if ($columnItem['data'] == 'id') {
                            $nestedData['id'] = $key + 1;
                        }

                        if ($columnItem['data'] == 'name') {
                            $nestedData['name'] = $item->company->company_name ?? "";
                        }

                        if ($columnItem['data'] == 'month') {
                            $nestedData['month'] =  $item->year  .'-'. date("F", mktime(0, 0, 0, $item->month, 1));
                        }

                        // if ($columnItem['data'] == 'package') {
                        //     $nestedData['package'] = $item->customer->getProfile->name;
                        // }

                        if ($columnItem['data'] == 'amount') {
                            $nestedData['amount'] = $item->charge;
                        }

                        if (array_key_exists('checked', $columnItem)) {
                            $nestedData[$columnItem['data']] = $this->checkedBtn($item->id, $routeName, $columnItem['data'], $item->$columnName, $columnItem['checked']);
                        }

                        if ($columnItem['data'] == 'action' && $show_action) {

                            $nestedData['action'] = '';

                            foreach ($actions as $action) {
                                if (!empty($action) && !is_array($action)) {
                                    if (strtolower($action) == 'show') {
                                        $nestedData['action'] .= $this->showBtn(route($routeName . '.show', $item->id));
                                    } else if (strtolower($action) == 'edit') {
                                        $nestedData['action'] .= $this->editBtn(route($routeName . '.edit', $item->id));
                                    } else if (strtolower($action) == 'destroy') {
                                        $nestedData['action'] .= $this->destroyBtn(route($routeName . '.destroy', $item->id));
                                    }
                                } else if (is_array($action)) {
                                    $nestedData['action'] .= $this->customBtn(
                                        route($routeName . '.' . $action['method_name'], ['company_id' => $item->company_id, 'month' => $item->month, 'year' => $item->year]),
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
