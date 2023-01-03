<div class="content-head">
    <div class="report-list">
        <ul>
            @if (Auth::user()->can('manage-trial-balance'))
                <li>
                    <a href="{{ route('journals.trialbalance') }}">
                        <i class="las la-balance-scale"></i>
                        <span>Trial Balance</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-profit-and-loss'))
                <li>
                    <a href="{{ route('journals.profitandloss') }}">
                        <i class="las la-coins"></i>
                        <span>Profit & Loss A/C</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-balance-sheet'))
                <li>
                    <a href="{{ route('journals.balancesheet') }}">
                        <i class="las la-file-alt"></i>
                        <span>Balance Sheet</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-ledgers')
                    || Auth::user()->can('view-journals')
                    || Auth::user()->can('view-accounts'))
                <li class="drops-items">
                    <a href="#">
                        <i class="las la-file-invoice-dollar"></i>
                        <span>Journal Vouchers</span>
                    </a>
                    <div class="sub-drops">
                        <b class="arrow"></b>
                        <ul>
                            <li><a href="{{ route('journals.index') }}">Journal Voucher</a></li>
                            <li><a href="{{ route('ledgers') }}">Accounting Ledgers</a></li>
                            <li><a href="{{ route('account.index') }}">Account Management</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->can('manage-reconciliation-statement'))
                <li>
                    <a href="{{ route('bankReconciliationStatement.index') }}">
                        <i class="las la-handshake"></i>
                        <span>Reconciliation Statement</span>
                    </a>
                </li>
            @endif

            @if (Auth::user()->can('manage-sales-register'))
                <li class="drops-items">
                    <a href="{{ route('salesRegister') }}">
                        <i class="las la-chart-bar"></i>
                        <span>Sales Register</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-sales-return-register'))
                <li>
                    <a href="{{ route('salesReturnRegister') }}">
                        <i class="las la-chart-line"></i>
                        <span>Sales Return Register</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-purchase-register'))
                <li>
                    <a href="{{ route('purchaseRegister') }}">
                        <i class="las la-shopping-cart"></i>
                        <span>Purchase Register</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-purchase-return-register'))
                <li>
                    <a href="{{ route('purchaseReturnRegister') }}">
                        <i class="las la-hand-holding-usd"></i>
                        <span>Purchase Return Register</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->can('manage-vat-refund'))
                <li>
                    <a href="{{ route('billing.vatrefund') }}">
                        <i class="las la-tags"></i>
                        <span>Vat Refund Report</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
    <!-- Mobile Report -->
    <div id="mySidenav" class="sidenav">
        <div class="mobile-logo">
            <span>Reports</span>
            <a href="javascript:void(0)" id="close-btn" class="closebtn">&times;</a>
        </div>
        <div class="no-bdr1">
            <ul id="menu1">
                <li><a href="{{ route('journals.trialbalance') }}"><i class="las la-balance-scale"></i> Trial Balance</a></li>
                <li><a href="{{ route('journals.profitandloss') }}"><i class="las la-coins"></i> Profit & Loss A/C</a></li>
                <li><a href="{{ route('journals.balancesheet') }}"><i class="las la-file-invoice-dollar"></i> Balance Sheet</a></li>
                <li>
                    <a href="#" class="has-arrow"><i class="las la-file-invoice-dollar"></i> Journal Vouchers</a>
                    <ul>
                        <li><a href="{{ route('journals.index') }}"><i class="las la-circle-notch"></i> Journal Voucher</a></li>
                        <li><a href="{{ route('ledgers') }}"><i class="las la-circle-notch"></i> Accounting Ledgers</a></li>
                        <li><a href="{{ route('account.index') }}"><i class="las la-circle-notch"></i> Account Management</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('bankReconciliationStatement.index') }}"><i class="las la-handshake"></i> Reconciliation Statement</a></li>
                <li><a href="{{ route('salesRegister') }}"><i class="las la-chart-bar"></i> Sales Register</a></li>
                <li><a href="{{ route('salesReturnRegister') }}"><i class="las la-chart-line"></i> Sales Return Register</a></li>
                <li><a href="{{ route('purchaseRegister') }}"><i class="las la-shopping-cart"></i> Purchase Register</a></li>
                <li><a href="{{ route('purchaseReturnRegister') }}"><i class="las la-hand-holding-usd"></i> Purchase Return Register</a></li>
                <li><a href="{{ route('billing.vatrefund') }}"><i class="las la-tags"></i> Vat Refund Report</a></li>
            </ul>
        </div>
    </div>
    <!-- Mobile Report End -->
</div>
