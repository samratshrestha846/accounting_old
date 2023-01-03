{{-- Help Modal --}}
<div class="modal fade" id="help" tabindex="-1" aria-labelledby="helpLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpLabel">Keyboard Shortcut</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table width="100%">
                    <tbody>
                        <tr>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><b>?</b></td>
                                            <td><span>Help Center</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>/</b></td>
                                            <td><span>Search Box</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>enter</b> </td>
                                            <td><span>Focus Next Input Field</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>↑</b></td>
                                            <td><span>Highlight Up Menu</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>↓</b></td>
                                            <td><span>Highlight Bottom Menu</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>j</b></td>
                                            <td><span>Journal Entry</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>p</b></td>
                                            <td><span>Purchase Create</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>shift</b> + <b>alt</b> + <b>p</b></td>
                                            <td><span>Product Create</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>t</b></td>
                                            <td><span>Trial Balance</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>h</b></td>
                                            <td><span>Go to Home Page</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><b>ctrl</b> + <b>←</b></td>
                                            <td><span>Highlight Left Menu</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>→</b></td>
                                            <td><span>Highlight Right Menu</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>←</b></td>
                                            <td><span>Go to Previous Page</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>→</b></td>
                                            <td><span>Go to Current Page</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>q</b></td>
                                            <td><span>Quotation Create</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>s</b></td>
                                            <td><span>Sales Create</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>d</b></td>
                                            <td><span>Service Sale Create</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>b</b></td>
                                            <td><span>Balance Sheet</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>l</b></td>
                                            <td><span>Profit and Loss A/C</span></td>
                                        </tr>
                                        <tr>
                                            <td><b>ctrl</b> + <b>alt</b> + <b>s</b></td>
                                            <td><span>Submit Form</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- Help Modal End --}}

<div class="content-head">
    <div class="report-list">
        <ul>
            @if (Auth::user()->can('view-products')
            || Auth::user()->can('create-product')
            || Auth::user()->can('edit-product')
            || Auth::user()->can('delete-product')
            || Auth::user()->can('manage-product-categories')
            || Auth::user()->can('manage-product-images'))
                <li class="drops-items r-list active">
                    <a href="#">
                        <i class="lab la-product-hunt"></i>
                        <span>Products</span>
                    </a>
                    <div class="sub-drops">
                        <b class="arrow"></b>
                        <ul>

                            <li><a href="{{route('product.create')}}">Create Products</a></li>
                            <li><a href="{{route('product.index')}}">View Products</a></li>
                            @if (Auth::user()->can('manage-product-categories'))
                            <li><a href="{{ route('category.index') }}">Product Categories</a></li>
                            @endif
                            @if (Auth::user()->can('manage-product-images'))
                            <li><a href="{{ route('fileManager') }}">Upload Product Images</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('manage-purchase-register')
                 ||Auth::user()->can('manage-purchase-return-register'))

                <li class="drops-items r-list">
                    <a href="#">
                        <i class="las la-shopping-cart"></i>
                        <span>Purchase</span>
                    </a>
                    <div class="sub-drops">
                        <b class="arrow"></b>
                        <ul class="navbar-nav">

                            <li><a href="{{route('billings.purchasecreate')}}">Create Purchase</a></li>
                            <li><a href="{{route('billings.report',2)}}">View Purchase</a></li>
                            @if (Auth::user()->can('manage-debit-note'))
                            <li><a href="{{ route('billings.report', 5) }}">View All Debit Note</a></li>
                            <li><a href="{{ route('service.purchasecreate') }}">Create New Service Purchase</a></li>
                            <li><a href="{{ route('service_sales.index',['billing_type_id'=>2]) }}">View Service Purchase</a></li>

                            @endif
                            @if (Auth::user()->can('manage-purchase-orders'))

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="las la-building m-only-icon"></i>
                                    <span class="c-names">
                                        Purchase Orders
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('purchaseOrder.create') }}">Create Purchase Order</a>
                                    <a class="dropdown-item" href="{{ route('purchaseOrder.client') }}">Client Purchase Orders</a>
                                    <a class="dropdown-item" href="{{ route('purchaseOrder.index') }}">All Purchase Orders</a>


                                </div>
                            </li>

                            @endif
                            <li><a href="{{ route('purchaseRegister') }}">Purchase Register</a></li>
                            <li><a href="{{ route('purchaseReturnRegister') }}">Purchase Return Register</a></li>
                        </ul>
                    </div>
                </li>

            @endif



            @if (Auth::user()->can('manage-sales-register')
                 ||Auth::user()->can('manage-sales-return-register'))

                <li class="drops-items r-list">
                    <a href="#">
                        <i class="las la-chart-bar"></i>
                        <span>Sales</span>
                    </a>
                    <div class="sub-drops">
                        <b class="arrow"></b>
                        <ul>
                            <li><a href="{{route('billings.salescreate')}}">Create Sale</a></li>
                            <li><a href="{{route('billings.report',1)}}">View Sale</a></li>
                            <li><a href="{{ route('billings.report', 6) }}">View All Credit Note</a></li>


                            <li><a href="{{route('service_sales.create')}}">Create Service Sale</a></li>
                            <li><a href="{{route('service_sales.index')}}">View Service Sale</a></li>

                            <li><a href="{{ route('salesRegister') }}">Sales Register</a></li>
                            <li><a href="{{ route('salesReturnRegister') }}">Sales Return Register</a></li>

                        </ul>
                    </div>
                </li>
            @endif

            <li class="drops-items r-list">
                <a href="#">
                    <i class="las la-file"></i>
                    <span>Voucher</span>
                </a>
                <div class="sub-drops">
                    <b class="arrow"></b>
                    <ul class="navbar-nav">
                        @if(Auth::user()->can('manage-payment'))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Payments
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('billings.report', 4) }}">Create new Payment</a>
                                <a class="dropdown-item" href="{{ route('billings.paymentcreate') }}">View Payments</a>

                            </div>
                        </li>
                        @endif
                        @if (Auth::user()->can('manage-receipts'))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Receipts
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('billings.report', 3) }}">Create new Receipts</a>
                                <a class="dropdown-item" href="{{ route('billings.receiptcreate') }}">View All Receipts</a>

                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>


            <li class="drops-items r-list">
                <a href="#">
                    <i class="las la-phone"></i>
                    <span>Contact</span>
                </a>
                <div class="sub-drops">
                    <b class="arrow"></b>
                    <ul class="navbar-nav">

                        @if (Auth::user()->can('view-supplier') || Auth::user()->can('create-supplier') || Auth::user()->can('edit-supplier') || Auth::user()->can('remove-supplier'))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Supplier
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('vendors.create') }}">Add new Supplier</a>
                                <a class="dropdown-item" href="{{ route('vendors.index') }}">View Suppliers</a>

                            </div>
                        </li>
                        @endif
                        @if (Auth::user()->can('view-customer') || Auth::user()->can('create-customer') || Auth::user()->can('edit-customer') || Auth::user()->can('remove-customer'))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Customer
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('client.create') }}">Add new Customer</a>
                                <a class="dropdown-item" href="{{ route('client.index') }}">View Customer</a>

                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>


            @if (Auth::user()->can('manage-ledgers')
                    || Auth::user()->can('view-journals')
                    || Auth::user()->can('view-accounts'))
                <li class="drops-items r-list">
                    <a href="#">
                        <i class="las la-file-invoice-dollar"></i>
                        <span>Journal Vouchers</span>
                    </a>
                    <div class="sub-drops">
                        <b class="arrow"></b>
                        <ul>
                            @if (Auth::user()->can('create-journals'))
                            <li><a href="{{ route('journals.create') }}">Entry Voucher</a></li>
                            @endif
                            <li><a href="{{ route('journals.index') }}">Journal Voucher</a></li>
                            @if (Auth::user()->can('unapproved-journals'))
                            <li><a href="{{ route('journals.unapproved') }}">Unapproved Voucher</a></li>
                            @endif
                            @if (Auth::user()->can('cancelled-journals'))
                            <li><a href="{{ route('journals.cancelled') }}">Cancelled Voucher</a></li>
                            @endif
                            <li><a href="{{ route('ledgers') }}">Accounting Ledgers</a></li>
                            <li><a href="{{ route('account.index') }}">Account Management</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            <li class="drops-items mega-drop r-list">
                <a href="#">
                    <i class="las la-file-medical-alt"></i>
                    <span>Reports</span>
                </a>
                <div class="multi-menus">
                    {{-- <div class="multi-menu-col">
                        <h3>Financial performance</h3>
                        <ul>
                            <li>
                                <a href="#" title="Budget Manager">Budget Manager</a>
                            </li>
                            <li>
                                <a href="#" title="Budget Summary">Budget Summary</a>
                            </li>
                            <li>
                                <a href="#" title="Budget Variance">Budget Variance</a>
                            </li>
                            <li>
                                <a href="#" title="Business Performance">Business Performance</a>
                            </li>
                            <li>
                                <a href="#" title="Tracking Summary">Tracking Summary</a>
                            </li>
                        </ul>
                    </div> --}}

                    <div class="multi-menu-col">
                        <h3>Payables & receivables</h3>
                        <ul>
                            <li>
                                <a href="{{route('supplierLedgersunpaid','unpaid')}}" title="">Purchase Unpaid Bills</a>
                            </li>
                            <li>
                                <a href="{{route('customersUnpaidLedgers')}}" title="">Sales Unpaid Bills</a>
                            </li>

                        </ul>
                    </div>
                    <div class="multi-menu-col">
                        <h3>Reconciliations</h3>
                        <ul>
                            <li>
                                <a href="{{ route('bankReconciliationStatement.index') }}">Reconciliation Statement</a>
                            </li>

                        </ul>
                    </div>
                    <div class="multi-menu-col">
                        <h3>Transactions</h3>
                        @if (Auth::user()->can('budget-setup') || Auth::user()->can('manage-budget-report'))
                        <ul>
                            <li>
                                <a href="{{route('budgetsetup')}}" title="Budget Manager">Budget
                                Setup
                                </a>
                            </li>
                            <li>
                                <a href="{{route('budgetinfo')}}" title="Budget Summary">Budget Report</a>
                            </li>


                        </ul>
                        @endif
                    </div>
                    <div class="multi-menu-col">
                        <h3>Billing Credits</h3>
                        @if (Auth::user()->can('budget-setup') || Auth::user()->can('manage-budget-report'))
                        <ul>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>2])}}" title="Purchase Credits">Purchase Credits

                                </a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>5])}}" title="Purchase Credits">Purchase Return Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>1])}}" title="Sales Credits">Sales Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>6])}}" title="Sales Return Credits">Sales Return Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>2,'is_service_sale'=>true])}}" title="Service Purchase Credits">Service Purchase Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>5,'is_service_sale'=>true])}}" title="Service Purchase Return Credits">Service Purchase Return Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>1,'is_service_sale'=>true])}}" title="Service Sale Credits">Service Sale Credits</a>
                            </li>
                            <li>
                                <a href="{{route('billing.billingcredits',['billing_type_id'=>6,'is_service_sale'=>true])}}" title="Service Sale Return Credits">Service Sale Return Credits</a>
                            </li>

                        </ul>
                        @endif
                    </div>
                    @if (Auth::user()->can('manage-trial-balance')
                    || Auth::user()->can('manage-profit-and-loss')
                    || Auth::user()->can('manage-balance-sheet')
                    || Auth::user()->can('manage-ledgers')
                    || Auth::user()->can('manage-reconciliation-statement'))
                    <div class="multi-menu-col">
                        <h3>Accounting Reports</h3>

                        <ul>
                            <li><a href="{{ route('journals.trialbalance') }}">Trial Balance</a></li>
                                <li><a href="{{ route('journals.profitandloss') }}">Profit & Loss A/C</a></li>
                                <li><a href="{{ route('journals.balancesheet') }}">Balance Sheet</a></li>
                                <li><a href="{{ route('ledgers') }}">Accounting Ledger</a></li>

                                <li><a href="{{ route('bankReconciliationStatement.index') }}">Reconciliation Statement</a></li>
                                @if (Auth::user()->can('manage-vat-refund'))
                                <li>
                                    <a href="{{ route('billing.vatrefund') }}">
                                        Vat Refund Report
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    @endif
                </div>
            </li>
            <li class="drops-items r-list">
                <a href="#">
                    <i class="las la-users"></i>
                    <span>Setting</span>
                </a>
                <div class="sub-drops">
                    <b class="arrow"></b>
                    <ul class="navbar-nav">
                        @if (Auth::user()->can('manage-company-setting')
                        || Auth::user()->can('manage-quotation-setting')
                        || Auth::user()->can('manage-pos-setting')
                        || Auth::user()->can('manage-offer-setting')
                        || Auth::user()->can('manage-discount-setting')
                        || Auth::user()->can('manage-credit-setting')
                        || Auth::user()->can('manage-payment-mode')
                        || Auth::user()->can('manage-units')
                        || Auth::user()->can('manage-tax')
                        || Auth::user()->can('manage-device')
                        || Auth::user()->can('manage-database-backup')
                        || Auth::user()->can('view-accounts')
                        || Auth::user()->can('edit-account')
                        || Auth::user()->can('create-account')
                        || Auth::user()->can('remove-account'))
                        @if(Auth::user()->id == 1)
                        <li><a href="{{ route('supersetting.edit', 1) }}">Super Setting</a></li>
                        <li><a href="{{ route('setting.index') }}">Lekhabidhi Setting</a></li>
                        @endif
                        <li><a href="{{route('fiscal_year.create')}}">Fiscal Year</a></li>
                        <li><a href="{{route('vat_refund.create')}}">Vat Refund</a></li>
                        @if (Auth::user()->can('scheme-management'))
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Scheme
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{route('scheme.create')}}">Create Schemes</a>
                                <a class="dropdown-item" href="{{route('scheme.index')}}">View Schemes</a>

                            </div>
                        </li>

                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Chart of Account
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('accounthierarchy') }}">Accounts Hierarchy</a>
                                <a class="dropdown-item" href="{{ route('account.index') }}">Accounts Types</a>
                                <a class="dropdown-item" href="{{ route('account.create') }}">Add Account Types</a>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="las la-building m-only-icon"></i>
                                <span class="c-names">
                                    Company Setting
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('company.index') }}">All Companies</a>
                                <a class="dropdown-item" href="{{ route('branch.index') }}">All Branches</a>
                            </div>
                        </li>
                        <li><a href="{{ route('quotation.setting', 1) }}">Quotation Setting</a></li>
                        <li><a href="{{ route('posSettings.index') }}">POS Setting</a></li>
                        <li><a href="{{ route('tax.index') }}">Tax Setting</a></li>
                        <li><a href="{{ route('offer.index') }}">Offer Setting</a></li>
                        <li><a href="{{ route('bankAccountType.index') }}">Bank Account Type Setting</a></li>
                        <li><a href="{{ route('discountSetting') }}">Discount Setting</a></li>
                        <li><a href="{{ route('unit.index') }}">Units</a></li>
                        <li><a href="{{ route('paymentmode.index') }}">Payment Mode</a></li>
                        <li><a href="{{ route('creditSettings') }}">Credit Setting</a></li>
                        <li><a href="{{ route('device.index') }}">Device</a></li>
                        <li><a href="{{ route('backup.index') }}">Backup Database</a></li>
                        @endif
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    <!-- Mobile Report -->
    <div id="mySidenav" class="sidenav">
        <div class="mobile-logo">
            <div class="mobile-title-view">
                <span>Reports</span>
                <a href="{{ route('pos.index') }}" class="btn btn-primary">
                    POS
                 </a>
            </div>
            <a href="javascript:void(0)" id="close-btn" class="closebtn">&times;</a>
        </div>
        <div class="no-bdr1">
            <ul id="menu1">
                <li>
                    <a href="#" class="has-arrow"><i class="las la-file-invoice-dollar"></i>Accounting Reports</a>
                    <ul>
                        <li><a href="{{ route('journals.trialbalance') }}">Trial Balance</a></li>
                        <li><a href="{{ route('journals.profitandloss') }}">Profit & Loss A/C</a></li>
                        <li><a href="{{ route('journals.balancesheet') }}">Balance Sheet</a></li>
                        <li><a href="{{ route('ledgers') }}">Accounting Ledger</a></li>
                        <li><a href="{{ route('bankReconciliationStatement.index') }}">Reconciliation Statement</a></li>

                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-file-invoice-dollar"></i> Journal Vouchers</a>
                    <ul>
                        <li><a href="{{ route('journals.index') }}"><i class="las la-circle-notch"></i> Journal Voucher</a></li>
                        <li><a href="{{ route('ledgers') }}"><i class="las la-circle-notch"></i> Accounting Ledgers</a></li>
                        <li><a href="{{ route('account.index') }}"><i class="las la-circle-notch"></i> Account Management</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-chart-bar"></i>Sales</a>
                    <ul>
                        <li><a href="{{ route('salesRegister') }}">Sales Register</a></li>
                        <li><a href="{{ route('salesReturnRegister') }}">Sales Return Register</a></li>
                        <li><a href="#" style="color:#3f6791">Product Sale</a></li>
                        <li><a href="{{route('billings.salescreate')}}">Create Sale</a></li>
                        <li><a href="{{route('billings.report',1)}}">View Sale</a></li>
                        <li><a href="#" style="color:#3f6791">Service Sale</a></li>
                        <li><a href="{{route('service_sales.create')}}">Create Sale</a></li>
                        <li><a href="{{route('service_sales.index')}}">View Sale</a></li>

                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-shopping-cart"></i>Purchase</a>
                    <ul>
                        <li><a href="{{ route('purchaseRegister') }}">Purchase Register</a></li>
                        <li><a href="{{ route('purchaseReturnRegister') }}">Purchase Return Register</a></li>
                        <li><a href="{{route('billings.purchasecreate')}}">Create Purchase</a></li>
                        <li><a href="{{route('billings.report',2)}}">View Purchase</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="lab la-product-hunt"></i>Products</a>
                    <ul>
                        <li><a href="{{route('product.create')}}">Create Products</a></li>
                        <li><a href="{{route('product.index')}}">View Products</a></li>

                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-users"></i>Scheme</a>
                    <ul>
                        <li><a href="{{route('scheme.create')}}">Create Schemes</a></li>
                        <li><a href="{{route('scheme.index')}}">View Schemes</a></li>

                    </ul>
                </li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-tags"></i>Vat Refund Report</a>
                    <ul>

                    </ul>
                </li>

            </ul>
        </div>
    </div>
    <!-- Mobile Report End -->
</div>
