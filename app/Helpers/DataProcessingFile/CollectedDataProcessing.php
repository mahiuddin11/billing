<?php

namespace App\Helpers\DataProcessingFile;

use App\Models\AccountTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CollectedDataProcessing
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
                ->with('getCustomer', 'transaction')
                ->orderBy($order, $dir);
            foreach ($tableColumnsName as $key => $tableColumns) {
                if (array_key_exists('customesearch', $tableColumns) && request('columns.' . $key . '.search.value')) {
                    $value = trim(request('columns.' . $key . '.search.value'), '"');
                    $results =  $results->where($tableColumns['customesearch'],  $value . "%");
                }

                if (array_key_exists('customesearch', $tableColumns) && request('columns.' . 7 . '.search.value')) {
                    $value = trim(request('columns.' . 7 . '.search.value'), '"');
                    $billing_id = AccountTransaction::where('type', 4)->where('company_id', auth()->user()->company_id)->whereDate('created_at', $value)->pluck('table_id');
                    $results = $results->whereIn('billings.id', $billing_id);
                }

                if (request('columns.' . 9 . '.search.value') && request('columns.' . 10 . '.search.value')) {
                    $from_date = trim(request('columns.' . 9 . '.search.value'), '"');
                    $to_date = trim(request('columns.' . 10 . '.search.value'), '"');
                    $billing_id = AccountTransaction::where('type', 4)->where('company_id', auth()->user()->company_id)->whereDate('created_at', ">=", $from_date)->whereDate('created_at', "<=", $to_date)->pluck('table_id');
                    $results = $results->whereIn('billings.id', $billing_id);
                }
            }
            $results = $results->get();
            $totalFiltered = $totalData;
        } else {
            $search = request('search.value');
            $results = $model->where('username', 'like', "%{$search}%");
            $results =  $results->orWhere('customers.queue_name', 'like', "%{$search}%");
            foreach ($tableColumnsName as $tableColumns) {
                if ($tableColumns['searchable'] && array_key_exists('data', $tableColumns)) {
                    $results =  $results->where('billings.status', '!=', 'unpaid')->orWhere($tableColumns['data'], 'like', "%{$search}%");
                }
            }
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
        // dd($results);
        if ($results) {
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
                        if ($columnItem['data'] == 'date_') {
                            $nestedData['date_'] = Carbon::parse($nestedData['date_'])->format('M-Y');
                        }


                        if ($columnItem['data'] == 'id') {
                            $nestedData['id'] = $key + 1;
                        }


                        if ($columnItem['data'] == 'username') {
                            $nestedData['username'] = ($item->username ?? $item->queue_name) . " (" .  ($item->getCustomer->client_id ?? "") . ")";
                        }

                        if ($columnItem['data'] == 'method') {
                            $nestedData['method'] = $item->payment_method_id == 500 ? 'Advance Pay' : $item->PaymentMethod->account_name ?? '';
                        }
                        if ($columnItem['data'] == 'pay_amount') {
                            $nestedData['pay_amount'] = $item->pay_amount ;
                        }

                        if ($columnItem['data'] == 'collected_date') {
                            $nestedData['collected_date'] = Carbon::parse($item->transaction->created_at ?? "")->format('Y-M-d h:i:s');
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
