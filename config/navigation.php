<?php

$menus = [
    [
        'label' => 'Setup',
        'route' => null,
        'access' => 'Setup',
        'icon' => 'fas fa-cog',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'District',
                'route' => 'district.index',
            ],
            [
                'label' => 'Upazila',
                'route' => 'upozilla.index',
            ],
            [
                'label' => 'Zone',
                'route' => 'zones.index',
            ],
            [
                'label' => 'Sub zones',
                'route' => 'subzones.index',
            ],
            [
                'label' => 'TJ Box',
                'route' => 'tjs.index',
            ],
            [
                'label' => 'Splitter',
                'route' => 'splitters.index',
            ],
            [
                'label' => 'Box',
                'route' => 'boxes.index',
            ],
            [
                'label' => 'PPP Profiles',
                'route' => 'm_p_p_p_profiles.index',
            ],
            [
                'label' => 'IP Pool',
                'route' => 'mpool.index',
            ],
            [
                'label' => 'Queue',
                'route' => 'queue.index',
            ],
            [
                'label' => 'Vlan',
                'route' => 'vlan.index',
            ],
            [
                'label' => 'Ip Address',
                'route' => 'ip_address.index',
            ],
            [
                'label' => 'Device',
                'route' => 'devices.index',
            ],
            // [
            //     'label' => 'Connection Type',
            //     'route' => 'connections.index',
            // ],
            [
                'label' => 'Client Type',
                'route' => 'client_types.index',
            ],
            // [
            //     'label' => 'Protocol Type',
            //     'route' => 'protocols.index',
            // ],
            [
                'label' => 'Packages',
                'route' => 'packages2.index',
            ],
            [
                'label' => 'Package',
                'route' => 'userpackage.index',
            ],
            [
                'label' => 'Billing Status',
                'route' => 'billingstatus.index',
            ],
            [
                'label' => 'Payment Methods',
                'route' => 'payments.index',
            ],
        ]
    ],

    // [
    //     'label' => 'Users',
    //     'route' => null,
    //     'icon' => 'fa fa-users',
    //     'parent_id' => 0,
    //     'submenu' => [
    //         [
    //             'label' => 'Create',
    //             'route' => 'users.create',
    //         ],
    //         [
    //             'label' => 'Users',
    //             'route' => 'users.index',
    //         ],
    //     ]
    // ],

    [
        'label' => 'Client',
        'route' => null,
        'access' => 'Client',
        'icon' => 'fas fa-users',
        'parent_id' => 0,
        'submenu' => [
            // [
            //     'label' => 'New Connection',
            //     'route' => 'newconnection.index',
            // ],
            [
                'label' => 'PPPOE Customer',
                'route' => 'customers.index',
            ],
            [
                'label' => 'Static Customer',
                'route' => 'static_customers.index',
            ],
            [
                'label' => 'General Customer',
                'route' => 'general_customers.index',
            ],
            [
                'label' => 'Advance Billing',
                'route' => 'advancebilling.index',
            ],
            [
                'label' => 'Active Connection',
                'route' => 'activeconnections.index',
            ],
            [
                'label' => 'Import Customer',
                'route' => 'mikrotiklist.index',
            ],

        ]
    ],
    [
        'label' => 'Billing',
        'route' => null,
        'access' => 'Billing',
        'icon' => 'fas fa-money-bill',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Pending List',
                'route' => 'billcollect.index',
            ],
            // [
            //     'label' => 'Confirm Bill',
            //     'route' => 'billconfirm.index',
            // ],
            [
                'label' => 'Collected List',
                'route' => 'billcollected.index',
            ],
            [
                'label' => 'Custom Bill',
                'route' => 'custombill.index',
            ],
            [
                'label' => 'Import Billings',
                'route' => 'imports.billings',
            ],

        ]
    ],
    [
        'label' => 'Mikrotik Server',
        'route' => null,
        'access' => 'MikrotikServer',
        'icon' => 'fab fa-servicestack',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Server',
                'route' => 'mikrotikserver.index',
            ],
            // [
            //     'label' => 'Import Customer',
            //     'route' => 'mikrotiklist.index',
            // ],
        ]
    ],

    [
        'label' => 'Mac Client',
        'route' => null,
        'access' => 'MacClient',
        'icon' => 'fa fa-users',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Package',
                'route' => 'macpackage.index',
            ],
            [
                'label' => 'Tariff Config',
                'route' => 'mactariffconfig.index',
            ],
            [
                'label' => 'Add Mac Reseller',
                'route' => 'macreseller.create',
            ],
            [
                'label' => 'All Mac Client',
                'route' => 'macreseller.index',
            ],
            [
                'label' => 'Reseller Invoice',
                'route' => 'resellerFunding.index',
            ],
            [
                'label' => 'Reseller Fund',
                'route' => 'addresellerfund.index',
            ],
        ]
    ],
    [
        'label' => 'Reseller',
        'route' => null,
        'access' => 'Reseller',
        'icon' => 'fa fa-users',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Item Cateory',
                'route' => 'itemcategory.index',
            ],
            [
                'label' => 'Item',
                'route' => 'items.index',
            ],
            [
                'label' => 'Clients',
                'route' => 'bandwidthCustomers.index',
            ],
            [
                'label' => 'Sale Invoice',
                'route' => 'bandwidthsaleinvoice.index',
            ],
        ]
    ],
    [
        'label' => 'Upstream',
        'route' => null,
        'access' => 'Upstream',
        'icon' => 'fas fa-industry',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Item Cateory',
                'route' => 'itemcategory.index',
            ],
            [
                'label' => 'Item',
                'route' => 'items.index',
            ],
            [
                'label' => 'Providers',
                'route' => 'providers.index',
            ],
            [
                'label' => 'Purchase Bill',
                'route' => 'purchasebill.index',
            ],
        ]
    ],
    [
        'label' => 'Support & Ticketing',
        'route' => null,
        'access' => 'SupportTicketing',
        'icon' => 'fas fa-ticket-alt',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Support Category',
                'route' => 'supportcategory.index',
            ],
            [
                'label' => 'Client Support',
                'route' => 'supportticket.index',
            ],
            [
                'label' => 'Client Support',
                'route' => 'employee.index',
            ],
        ]
    ],
    [
        'label' => 'Accounting',
        'route' => null,
        'access' => 'Accounting',
        'icon' => 'fas fa-calculator',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Accounts Head',
                'route' => 'accounts.index',
            ],
            [
                'label' => 'Opening Balance',
                'route' => 'openingbalance.index',
            ],
            [
                'label' => 'Balance Transfer',
                'route' => 'balancetransfer.index',
            ],
            [
                'label' => 'Bill Transfer',
                'route' => 'billtransfer.index',
            ],
            [
                'label' => 'Supplier Ledger',
                'route' => 'supplier_ledger.index',
            ],
            [
                'label' => 'Reseller Payment',
                'route' => 'resellerFunding.paymentCreate',
            ],
        ]
    ],
    [
        'label' => 'HR & PAYROLL',
        'route' => null,
        'access' => 'HRPAYROLL',
        'icon' => 'fas fa-users-cog',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Department',
                'route' => 'department.index',
            ],
            [
                'label' => 'Designation',
                'route' => 'designation.index',
            ],
            [
                'label' => 'Employee',
                'route' => 'employees.index',
            ],
            [
                'label' => 'Attendance Form',
                'route' => 'hrm.attendance.create',
            ],
            [
                'label' => 'Attendance Log',
                'route' => 'hrm.attendancelog.index',
            ],

            [
                'label' => 'Salary Sheet',
                'route' => 'hrm.salarysheetlog.index',
            ],
            [
                'label' => 'Leave Application',
                'route' => 'leaveApplication.index',
            ],

            [
                'label' => 'Leave Approve',
                'route' => 'leaveApplicationApprove.index',
            ],

            [
                'label' => 'Lone Application',
                'route' => 'loneApplication.index',
            ],

            [
                'label' => 'Lone Approve',
                'route' => 'loneApplicationApprove.index',
            ],
        ]
    ],

    //


    [
        'label' => 'Inventory Setup',
        'route' => null,
        'access' => 'InventorySetup',
        'icon' => 'fas fa-warehouse',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'All Products',
                'route' => 'products.index',
            ],
            [
                'label' => 'Create Products',
                'route' => 'products.create',
            ],
            [
                'label' => 'All Product Category',
                'route' => 'productCategory.index',
            ],
            [
                'label' => 'Create  Category',
                'route' => 'productCategory.create',
            ],

            [
                'label' => 'All Unit',
                'route' => 'units.index',
            ],
            [
                'label' => 'Create Unit',
                'route' => 'units.create',
            ],

            [
                'label' => 'All Brands',
                'route' => 'brands.index',
            ],
            [
                'label' => 'Create Brands',
                'route' => 'brands.create',
            ],

        ]
    ],
    [
        'label' => 'Inventory Management',
        'route' => null,
        'access' => 'InventoryManagement',
        'icon' => 'fas fa-boxes',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Purchase Requisition',
                'route' => 'purchaseRequisition.index',
            ],
            [
                'label' => 'All Purchases',
                'route' => 'purchases.index',
            ],
            [
                'label' => 'Create Purchases',
                'route' => 'purchases.create',
            ],
            [
                'label' => 'Stock Details',
                'route' => 'purchases.stock.list',
            ],
            [
                'label' => 'All Stock Out',
                'route' => 'stockout.index',
            ],
            [
                'label' => 'Create Stock Out',
                'route' => 'stockout.create',
            ],


        ]
    ],
    [
        'label' => 'Supplier',
        'route' => null,
        'access' => 'Supplier',
        'icon' => 'fa fa-users',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'All Supplier',
                'route' => 'suppliers.index',
            ],
            [
                'label' => 'Create Supplier',
                'route' => 'suppliers.create',
            ],
        ]
    ],
    ///
    [
        'label' => 'Income',
        'route' => null,
        'access' => 'Income',
        'icon' => 'fas fa-wallet',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Income Category',
                'route' => 'incomeCategory.index',
            ],
            [
                'label' => 'Daily Income',
                'route' => 'dailyIncome.index',
            ],
            // [
            //     'label' => 'Income History',
            //     'route' => 'incomeHistory.index',
            // ],
            // [
            //     'label' => 'Installation Fee',
            //     'route' => 'installationFee.index',
            // ],
        ]
    ],

    [
        'label' => 'Expense',
        'route' => null,
        'access' => 'Expense',
        'icon' => 'fas fa-credit-card',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Expense Category',
                'route' => 'expense_category.index',
            ],
            [
                'label' => 'Create Expense Category',
                'route' => 'expense_category.create',
            ],
            [
                'label' => 'Expense List',
                'route' => 'expenses.index',
            ],
            [
                'label' => 'Create Expense',
                'route' => 'expenses.create',
            ],
        ]
    ],
    [
        'label' => 'Asset Management',
        'route' => null,
        'access' => 'AssetManagement',
        'icon' => 'fas fa-tasks',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'Asset Category',
                'route' => 'assets.index',
            ],
            // [
            //     'label' => 'Asset Category Create',
            //     'route' => 'assets.create',
            // ],
            [
                'label' => 'Reason List',
                'route' => 'reasons.index',
            ],
            [
                'label' => 'Asset List',
                'route' => 'assetlist.index',
            ],
            [
                'label' => 'Destroy Items',
                'route' => 'destroyitems.index',
            ],
        ]
    ],

    [
        'label' => 'Reports',
        'route' => null,
        'access' => 'Reports',
        'icon' => 'fas fa-border-none',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'BTRC',
                'route' => 'reports.btrc',
            ],
            [
                'label' => 'Discount',
                'route' => 'reports.discounts',
            ],
            [
                'label' => 'Bill Collect',
                'route' => 'reports.bill.index',
            ],
            [
                'label' => 'Mac Client',
                'route' => 'reports.mac.reseller',
            ],
            [
                'label' => 'Customer',
                'route' => 'reports.customers',
            ],
            [
                'label' => 'Reseller',
                'route' => 'reports.reseller',
            ],
            [
                'label' => 'Upstream',
                'route' => 'reports.upstream',
            ],
            [
                'label' => 'Cash Book',
                'route' => 'report.cashbook',
            ],
            [
                'label' => 'Ledger',
                'route' => 'report.ledger',
            ],
            [
                'label' => 'Trial Balance',
                'route' => 'report.trialbalance',
            ],
            [
                'label' => 'Income Statement',
                'route' => 'report.incomestatement',
            ],
            [
                'label' => 'Balance Sheet',
                'route' => 'report.balancesheet',
            ],
        ]
    ],

    [
        'label' => 'SMS Service',
        'route' => null,
        'access' => 'Reports',
        'icon' => 'fas fa-sms',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'All Sms',
                'route' => 'sms.index',
            ],
            [
                'label' => 'Create Sms',
                'route' => 'sms.create',
            ],
        ]
    ],

    [
        'label' => 'System',
        'route' => null,
        'access' => 'System',
        'icon' => 'fa fa-cogs',
        'parent_id' => 0,
        'submenu' => [
            [
                'label' => 'User Roll',
                'route' => 'rollPermission.index',
            ],
            [
                'label' => 'Company Setup',
                'route' => 'companies.index',
            ],
        ]
    ],

    // [
    //     'label' => 'Support',
    //     'access' => 'Support',
    //     'route' => 'ticketing.index',
    //     'icon' => 'fa fa-cogs',
    //     'parent_id' => 10000,
    //     'submenu' => []
    // ],
    // [
    //     'label' => 'Support History',
    //     'access' => 'SupportHistory',
    //     'route' => 'supporthistory.index',
    //     'icon' => 'fa fa-cogs',
    //     'parent_id' => 10000,
    //     'submenu' => [],
    // ],
    // [
    //     'label' => 'Billing Details',
    //     'route' => 'billing_details.index',
    //     'icon' => 'fa fa-cogs',
    //     'parent_id' => 10000,
    //     'submenu' => [],
    // ],
    // [
    //     'label' => 'Package Update and Down Rate',
    //     'route' => 'package_update_and_down_rate.index',
    //     'icon' => 'fa fa-cogs',
    //     'parent_id' => 10000,
    //     'submenu' => [],
    // ],

];

return $menus;
