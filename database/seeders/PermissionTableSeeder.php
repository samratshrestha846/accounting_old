<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    private function getLabPermission(): array
    {
        return [
            ['column_name' => 'Designation', 'name' => 'Designation View', 'slug' => Str::slug('Designation  View')],
            ['column_name' => 'Designation', 'name' => 'Designation Create', 'slug' => Str::slug('Designation  Create')],
            ['column_name' => 'Designation', 'name' => 'Designation Edit', 'slug' => Str::slug('Designation  Edit')],
            ['column_name' => 'Designation', 'name' => 'Designation Delete', 'slug' => Str::slug('Designation  Delete')],

            ['column_name' => 'hospital staff', 'name' => 'Hospital Staff View', 'slug' => Str::slug('Hospital Staff  View')],
            ['column_name' => 'hospital staff', 'name' => 'Hospital Staff Create', 'slug' => Str::slug('Hospital Staff  Create')],
            ['column_name' => 'hospital staff', 'name' => 'Hospital Staff Edit', 'slug' => Str::slug('Hospital Staff  Edit')],
            ['column_name' => 'hospital staff', 'name' => 'Hospital Staff Delete', 'slug' => Str::slug('Hospital Staff  Delete')],

            ['column_name' => 'lab', 'name' => 'Lab View', 'slug' => Str::slug('Lab  View')],
            ['column_name' => 'lab', 'name' => 'Lab Create', 'slug' => Str::slug('Lab  Create')],
            ['column_name' => 'lab', 'name' => 'Lab Edit', 'slug' => Str::slug('Lab  Edit')],
            ['column_name' => 'lab', 'name' => 'Lab Delete', 'slug' => Str::slug('Lab  Delete')],

            ['column_name' => 'Patient', 'name' => 'Patient View', 'slug' => Str::slug('Patient  View')],
            ['column_name' => 'Patient', 'name' => 'Patient Create', 'slug' => Str::slug('Patient  Create')],
            ['column_name' => 'Patient', 'name' => 'Patient Edit', 'slug' => Str::slug('Patient  Edit')],
            ['column_name' => 'Patient', 'name' => 'Patient Delete', 'slug' => Str::slug('Patient  Delete')],

            ['column_name' => 'Medical History', 'name' => 'Medical History View', 'slug' => Str::slug('Medical History  View')],
            ['column_name' => 'Medical History', 'name' => 'Medical History Create', 'slug' => Str::slug('Medical History  Create')],
            ['column_name' => 'Medical History', 'name' => 'Medical History Edit', 'slug' => Str::slug('Medical History  Edit')],
            ['column_name' => 'Medical History', 'name' => 'Medical History Delete', 'slug' => Str::slug('Medical History  Delete')],

            ['column_name' => 'Appointments ', 'name' => 'Appointments  View', 'slug' => Str::slug('Appointments   View')],
            ['column_name' => 'Appointments ', 'name' => 'Appointments  Create', 'slug' => Str::slug('Appointments   Create')],
            ['column_name' => 'Appointments ', 'name' => 'Appointments  Edit', 'slug' => Str::slug('Appointments   Edit')],
            ['column_name' => 'Appointments ', 'name' => 'Appointments  Delete', 'slug' => Str::slug('Appointments   Delete')],


        ];
    }



    public function getHotelPermission(): array
    {
        return [
            ['column_name' => 'POS Management ', 'name' => 'Hotel Order Invoice', 'slug' => Str::slug('Hotel Order Invoice')],

            ['column_name' => 'Cabin Type', 'name' => 'Cabin Type View', 'slug' => Str::slug('Cabin Type View')],
            ['column_name' => 'Cabin Type', 'name' => 'Cabin Type Create', 'slug' => Str::slug('Cabin Type Create')],
            ['column_name' => 'Cabin Type', 'name' => 'Cabin Type Edit', 'slug' => Str::slug('Cabin Type Edit')],
            ['column_name' => 'Cabin Type', 'name' => 'Cabin Type Delete', 'slug' => Str::slug('Cabin Type Delete')],


            ['column_name' => 'Hotel Food', 'name' => 'Hotel Food View', 'slug' => Str::slug('Hotel Food View')],
            ['column_name' => 'Hotel Food', 'name' => 'Hotel Food Create', 'slug' => Str::slug('Hotel Food Create')],
            ['column_name' => 'Hotel Food', 'name' => 'Hotel Food Edit', 'slug' => Str::slug('Hotel Food Edit')],
            ['column_name' => 'Hotel Food', 'name' => 'Hotel Food Delete', 'slug' => Str::slug('Hotel Food Delete')],

            ['column_name' => 'Hotel Floor', 'name' => 'Hotel Floor View', 'slug' => Str::slug('Hotel Floor View')],
            ['column_name' => 'Hotel Floor', 'name' => 'Hotel Floor Create', 'slug' => Str::slug('Hotel Floor Create')],
            ['column_name' => 'Hotel Floor', 'name' => 'Hotel Floor Edit', 'slug' => Str::slug('Hotel Floor Edit')],
            ['column_name' => 'Hotel Floor', 'name' => 'Hotel Floor Delete', 'slug' => Str::slug('Hotel Floor Delete')],

            ['column_name' => 'Hotel Room', 'name' => 'Hotel Room View', 'slug' => Str::slug('Hotel Room View')],
            ['column_name' => 'Hotel Room', 'name' => 'Hotel Room Create', 'slug' => Str::slug('Hotel Room Create')],
            ['column_name' => 'Hotel Room', 'name' => 'Hotel Room Edit', 'slug' => Str::slug('Hotel Room Edit')],
            ['column_name' => 'Hotel Room', 'name' => 'Hotel Room Delete', 'slug' => Str::slug('Hotel Room Delete')],

            ['column_name' => 'Hotel Table', 'name' => 'Hotel Table View', 'slug' => Str::slug('Hotel Table View')],
            ['column_name' => 'Hotel Table', 'name' => 'Hotel Table Create', 'slug' => Str::slug('Hotel Table Create')],
            ['column_name' => 'Hotel Table', 'name' => 'Hotel Table Edit', 'slug' => Str::slug('Hotel Table Edit')],
            ['column_name' => 'Hotel Table', 'name' => 'Hotel Table Delete', 'slug' => Str::slug('Hotel Table Delete')],

            ['column_name' => 'Hotel Kitchen', 'name' => 'Hotel Kitchen View', 'slug' => Str::slug('Hotel Kitchen View')],
            ['column_name' => 'Hotel Kitchen', 'name' => 'Hotel Kitchen Create', 'slug' => Str::slug('Hotel Kitchen Create')],
            ['column_name' => 'Hotel Kitchen', 'name' => 'Hotel Kitchen Edit', 'slug' => Str::slug('Hotel Kitchen Edit')],
            ['column_name' => 'Hotel Kitchen', 'name' => 'Hotel Kitchen Delete', 'slug' => Str::slug('Hotel Kitchen Delete')],

            ['column_name' => 'Hotel Reservation', 'name' => 'Hotel Reservation View', 'slug' => Str::slug('Hotel Reservation View')],
            ['column_name' => 'Hotel Reservation', 'name' => 'Hotel Reservation Create', 'slug' => Str::slug('Hotel Reservation Create')],
            ['column_name' => 'Hotel Reservation', 'name' => 'Hotel Reservation Edit', 'slug' => Str::slug('Hotel Reservation Edit')],
            ['column_name' => 'Hotel Reservation', 'name' => 'Hotel Reservation Delete', 'slug' => Str::slug('Hotel Reservation Delete')],

            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order View', 'slug' => Str::slug('Hotel Order View')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Create', 'slug' => Str::slug('Hotel Order Create')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Edit', 'slug' => Str::slug('Hotel Order Edit')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Delete', 'slug' => Str::slug('Hotel Order Delete')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Sales', 'slug' => Str::slug('Hotel Order Sales')],
            
            
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Print', 'slug' => Str::slug('Hotel Order Print')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Cancelled', 'slug' => Str::slug('Hotel Order Cancelled')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Restore', 'slug' => Str::slug('Hotel Order Restored')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Payment', 'slug' => Str::slug('Hotel Order Payment')],
            ['column_name' => 'Hotel Order', 'name' => 'Hotel Order Suspend', 'slug' => Str::slug('Hotel Order Suspended')],

        ];
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        Permission::insert([
            [
                'column_name' => 'accounts',
                'name' => 'View Accounts',
                'slug' => Str::slug('View Accounts'),
            ],
            [
                'column_name' => 'accounts',
                'name' => 'Create Account',
                'slug' => Str::slug('Create Account'),
            ],
            [
                'column_name' => 'accounts',
                'name' => 'Edit Account',
                'slug' => Str::slug('Edit Account'),
            ],
            [
                'column_name' => 'accounts',
                'name' => 'Remove Account',
                'slug' => Str::slug('Remove Account'),
            ],

            [
                'column_name' => 'journals',
                'name' => 'View Journals',
                'slug' => Str::slug('View Journals'),
            ],
            [
                'column_name' => 'journals',
                'name' => 'Create Journals',
                'slug' => Str::slug('Create Journals'),
            ],
            [
                'column_name' => 'journals',
                'name' => 'Edit Journals',
                'slug' => Str::slug('Edit Journals'),
            ],
            [
                'column_name' => 'journals',
                'name' => 'Unapproved Journals',
                'slug' => Str::slug('Unapproved Journals'),
            ],
            [
                'column_name' => 'journals',
                'name' => 'Cancelled Journals',
                'slug' => Str::slug('Cancelled Journals'),
            ],
            [
                'column_name' => 'quotations',
                'name' => 'Manage Quotations',
                'slug' => Str::slug('Manage Quotations'),
            ],

            [
                'column_name' => 'purchase',
                'name' => 'Manage Purchases',
                'slug' => Str::slug('Manage Purchases'),
            ],

            [
                'column_name' => 'purchase',
                'name' => 'Manage Debit Note',
                'slug' => Str::slug('Manage Debit Note'),
            ],

            [
                'column_name' => 'purchase',
                'name' => 'Manage Purchase Orders',
                'slug' => Str::slug('Manage Purchase Orders'),
            ],

            [
                'column_name' => 'purchase',
                'name' => 'Manage Payment',
                'slug' => Str::slug('Manage Payment'),
            ],
            [
                'column_name' => 'suppliers',
                'name' => 'View Supplier',
                'slug' => Str::slug('View Supplier'),
            ],
            [
                'column_name' => 'suppliers',
                'name' => 'Create Supplier',
                'slug' => Str::slug('Create Supplier'),
            ],
            [
                'column_name' => 'suppliers',
                'name' => 'Edit Supplier',
                'slug' => Str::slug('Edit Supplier'),
            ],
            [
                'column_name' => 'suppliers',
                'name' => 'Remove Supplier',
                'slug' => Str::slug('Remove Supplier'),
            ],

            [
                'column_name' => 'sales',
                'name' => 'Manage Sales',
                'slug' => Str::slug('Manage Sales'),
            ],

            [
                'column_name' => 'sales',
                'name' => 'Manage Credit Note',
                'slug' => Str::slug('Manage Credit Note'),
            ],

            [
                'column_name' => 'sales',
                'name' => 'Manage Sales Invoices',
                'slug' => Str::slug('Manage Sales Invoices'),
            ],
            [
                'column_name' => 'sales',
                'name' => 'Manage Service Sales',
                'slug' => Str::slug('Manage Service Sales'),
            ],

            [
                'column_name' => 'sales',
                'name' => 'Manage Receipts',
                'slug' => Str::slug('Manage Receipts'),
            ],

            [
                'column_name' => 'customers',
                'name' => 'View Customer',
                'slug' => Str::slug('View Customer'),
            ],
            [
                'column_name' => 'customers',
                'name' => 'Create Customer',
                'slug' => Str::slug('Create Customer'),
            ],
            [
                'column_name' => 'customers',
                'name' => 'Edit Customer',
                'slug' => Str::slug('Edit Customer'),
            ],
            [
                'column_name' => 'customers',
                'name' => 'Remove Customer',
                'slug' => Str::slug('Remove Customer'),
            ],
            [
                'column_name' => 'customers',
                'name' => 'Manage Dealer',
                'slug' => Str::slug('Manage Dealer'),
            ],

            [
                'column_name' => 'products',
                'name' => 'Manage Product Categories',
                'slug' => Str::slug('Manage Product Categories'),
            ],

            [
                'column_name' => 'products',
                'name' => 'View Products',
                'slug' => Str::slug('View Products'),
            ],

            [
                'column_name' => 'products',
                'name' => 'Create Product',
                'slug' => Str::slug('Create Product'),
            ],
            [
                'column_name' => 'products',
                'name' => 'Edit Product',
                'slug' => Str::slug('Edit Product'),
            ],
            [
                'column_name' => 'products',
                'name' => 'Delete Product',
                'slug' => Str::slug('Delete Product'),
            ],

            [
                'column_name' => 'products',
                'name' => 'Manage Product Images',
                'slug' => Str::slug('Manage Product Images'),
            ],

            [
                'column_name' => 'stock-management',
                'name' => 'Manage Godown Information',
                'slug' => Str::slug('Manage Godown Information'),
            ],

            [
                'column_name' => 'stock-management',
                'name' => 'Manage Brand Information',
                'slug' => Str::slug('Manage Brand Information'),
            ],
            [
                'column_name' => 'stock-management',
                'name' => 'Manage Series Information',
                'slug' => Str::slug('Manage Series Information'),
            ],

            [
                'column_name' => 'stock-management',
                'name' => 'Manage Damaged Product',
                'slug' => Str::slug('Manage Damaged Product'),
            ],

            [
                'column_name' => 'stock-management',
                'name' => 'Manage Product Report',
                'slug' => Str::slug('Manage Product Report'),
            ],

            [
                'column_name' => 'service-management',
                'name' => 'Manage Service Categories',
                'slug' => Str::slug('Manage Service Categories'),
            ],
            [
                'column_name' => 'service-management',
                'name' => 'Manage Services',
                'slug' => Str::slug('Manage Services'),
            ],

            [
                'column_name' => 'pos-management',
                'name' => 'Manage Pos',
                'slug' => Str::slug('Manage Pos'),
            ],
            [
                'column_name' => 'pos-management',
                'name' => 'Manage Suspended Bill',
                'slug' => Str::slug('Manage Suspended Bill'),
            ],

            [
                'column_name' => 'share-management',
                'name' => 'Manage Company Shares',
                'slug' => Str::slug('Manage Company Shares'),
            ],
            [
                'column_name' => 'share-management',
                'name' => 'Manage Personal Shares',
                'slug' => Str::slug('Manage Personal Shares'),
            ],

            [
                'column_name' => 'credit-management',
                'name' => 'Manage Credit',
                'slug' => Str::slug('Manage Credit'),
            ],

            [
                'column_name' => 'loan-management',
                'name' => 'Loan Management',
                'slug' => Str::slug('Loan Management'),
            ],

            [
                'column_name' => 'scheme-management',
                'name' => 'Scheme Management',
                'slug' => Str::slug('Scheme Management'),
            ],

            [
                'column_name' => 'staff-management',
                'name' => 'Manage Positions',
                'slug' => Str::slug('Manage Positions'),
            ],
            [
                'column_name' => 'staff-management',
                'name' => 'Manage Department',
                'slug' => Str::slug('Manage Department'),
            ],

            [
                'column_name' => 'staff-management',
                'name' => 'Manage Staffs',
                'slug' => Str::slug('Manage Staffs'),
            ],

            [
                'column_name' => 'staff-management',
                'name' => 'Manage Attendance',
                'slug' => Str::slug('Manage Attendance'),
            ],

            [
                'column_name' => 'staff-management',
                'name' => 'Manage Attendance Report',
                'slug' => Str::slug('Manage Attendance Report'),
            ],

            [
                'column_name' => 'staff-management',
                'name' => 'Manage Payroll',
                'slug' => Str::slug('Manage Payroll'),
            ],

            [
                'column_name' => 'bank-information',
                'name' => 'View Bank Info',
                'slug' => Str::slug('View Bank Info'),
            ],
            [
                'column_name' => 'bank-information',
                'name' => 'Create Bank Info',
                'slug' => Str::slug('Create Bank Info'),
            ],

            [
                'column_name' => 'bank-information',
                'name' => 'Edit Bank Info',
                'slug' => Str::slug('Edit Bank Info'),
            ],

            [
                'column_name' => 'bank-information',
                'name' => 'Delete Bank Info',
                'slug' => Str::slug('Delete Bank Info'),
            ],

            [
                'column_name' => 'online-payment',
                'name' => 'Manage Online Payment',
                'slug' => Str::slug('Manage Online Payment'),
            ],

            [
                'column_name' => 'daily-expenses',
                'name' => 'View Daily Expenses',
                'slug' => Str::slug('View Daily Expenses'),
            ],
            [
                'column_name' => 'daily-expenses',
                'name' => 'Create Daily Expenses',
                'slug' => Str::slug('Create Daily Expenses'),
            ],
            [
                'column_name' => 'daily-expenses',
                'name' => 'Edit Daily Expenses',
                'slug' => Str::slug('Edit Daily Expenses'),
            ],
            [
                'column_name' => 'daily-expenses',
                'name' => 'Remove Daily Expenses',
                'slug' => Str::slug('Remove Daily Expenses'),
            ],

            [
                'column_name' => 'user-management',
                'name' => 'View User',
                'slug' => Str::slug('View User'),
            ],
            [
                'column_name' => 'user-management',
                'name' => 'Create User',
                'slug' => Str::slug('Create User'),
            ],
            [
                'column_name' => 'user-management',
                'name' => 'Edit User',
                'slug' => Str::slug('Edit User'),
            ],
            [
                'column_name' => 'user-management',
                'name' => 'Remove User',
                'slug' => Str::slug('Remove User'),
            ],

            [
                'column_name' => 'role-management',
                'name' => 'View Role',
                'slug' => Str::slug('View Role'),
            ],
            [
                'column_name' => 'role-management',
                'name' => 'Create Role',
                'slug' => Str::slug('Create Role'),
            ],
            [
                'column_name' => 'role-management',
                'name' => 'Edit Role',
                'slug' => Str::slug('Edit Role'),
            ],
            [
                'column_name' => 'role-management',
                'name' => 'Remove Role',
                'slug' => Str::slug('Remove Role'),
            ],

            [
                'column_name' => 'accounting-reports',
                'name' => 'Manage Trial Balance',
                'slug' => Str::slug('Manage Trial Balance'),
            ],
            [
                'column_name' => 'accounting-reports',
                'name' => 'Manage Profit and Loss',
                'slug' => Str::slug('Manage Profit and Loss'),
            ],
            [
                'column_name' => 'accounting-reports',
                'name' => 'Manage Balance Sheet',
                'slug' => Str::slug('Manage Balance Sheet'),
            ],
            [
                'column_name' => 'accounting-reports',
                'name' => 'Manage Ledgers',
                'slug' => Str::slug('Manage Ledgers'),
            ],
            [
                'column_name' => 'accounting-reports',
                'name' => 'Manage Reconciliation Statement',
                'slug' => Str::slug('Manage Reconciliation Statement'),
            ],

            [
                'column_name' => 'register-reports',
                'name' => 'Manage Sales Register',
                'slug' => Str::slug('Manage Sales Register'),
            ],

            [
                'column_name' => 'register-reports',
                'name' => 'Manage Sales Return Register',
                'slug' => Str::slug('Manage Sales Return Register'),
            ],

            [
                'column_name' => 'register-reports',
                'name' => 'Manage Purchase Register',
                'slug' => Str::slug('Manage Purchase Register'),
            ],

            [
                'column_name' => 'register-reports',
                'name' => 'Manage Purchase Return Register',
                'slug' => Str::slug('Manage Purchase Return Register'),
            ],
            [
                'column_name' => 'financial-reports',
                'name' => 'Manage VAT Refund',
                'slug' => Str::slug('Manage VAT Refund'),
            ],

            [
                'column_name' => 'financial-reports',
                'name' => 'Manage Tax Information',
                'slug' => Str::slug('Manage Tax Information'),
            ],

            [
                'column_name' => 'budget',
                'name' => 'Budget Setup',
                'slug' => Str::slug('Budget Setup'),
            ],
            [
                'column_name' => 'budget',
                'name' => 'Manage Budget Report',
                'slug' => Str::slug('Manage Budget Report'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Company Setting',
                'slug' => Str::slug('Manage Company Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Quotation Setting',
                'slug' => Str::slug('Manage Quotation Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Payment Mode',
                'slug' => Str::slug('Manage Payment Mode'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Units',
                'slug' => Str::slug('Manage Units'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage POS Setting',
                'slug' => Str::slug('Manage POS Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Offer Setting',
                'slug' => Str::slug('Manage Offer Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Discount Setting',
                'slug' => Str::slug('Manage Discount Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Credit Setting',
                'slug' => Str::slug('Manage Credit Setting'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Tax',
                'slug' => Str::slug('Manage Tax'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Device',
                'slug' => Str::slug('Manage Device'),
            ],

            [
                'column_name' => 'settings',
                'name' => 'Manage Database Backup',
                'slug' => Str::slug('Manage Database Backup'),
            ],

            [
                'column_name' => 'trash',
                'name' => 'Manage Trash',
                'slug' => Str::slug('Manage Trash'),
            ],


        ]);
        Permission::insert($this->getLabPermission());
        Permission::insert($this->getHotelPermission());
    }
}
