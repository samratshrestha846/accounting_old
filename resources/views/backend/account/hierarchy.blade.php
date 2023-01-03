@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>Account Hierarchy</h1>
                </div><!-- /.row -->

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th rowspan="2" style="width: 300px;">Accounts</th>
                                                </tr>
                                            </thead>
                                            @foreach ($mainaccounts as $account)

                                                <tbody>
                                                    <tr class="main tr-light" style="background-color: #e9ecef;">
                                                        <td>
                                                            <a href="#" class="drop"><i
                                                                    class="main far fa-folder"></i></a>
                                                            <b>{{ $account->title }}</b>
                                                        </td>
                                                    </tr>
                                                    @foreach ($account->main_sub_accounts as $subAccount)
                                                        @php
                                                            $journalextras = [];
                                                            $subChild = $subAccount->child_accounts;

                                                            $subaccountsinside = $subAccount->sub_accounts_inside($subAccount->id);
                                                            // dd($subaccountsinside);
                                                        @endphp
                                                        <tr class="sub display">
                                                            <td><a href="#" class="sub-drop"><i
                                                                        class="subicon far fa-folder"></i></a>
                                                                {{ $subAccount->title }}
                                                            </td>
                                                        </tr>
                                                        @foreach ($subaccountsinside as $subaccountinside)
                                                            <tr class="subinside display">
                                                                <td><a href="#" class="subinside-drop"><i
                                                                            class="subinsideicon far fa-folder"></i></a>
                                                                    {{ $subaccountinside->title }}
                                                                </td>
                                                            </tr>
                                                            @foreach ($subaccountinside->child_accounts as $insidechildAccount)
                                                                <tr class="insidesingle display">
                                                                    <td>
                                                                        <a href="#" class="insidesingle-drop"><i
                                                                                class="far fa-file"></i></a>
                                                                        <em>{{ $insidechildAccount->title }}</em>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                        @foreach ($subAccount->child_accounts as $childAccount)
                                                            <tr class="single display">
                                                                <td>
                                                                    <a href="#" class="single-drop"><i
                                                                            class="far fa-file"></i></a>
                                                                    <em>{{ $childAccount->title }}</em>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>

                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        $(function() {
            var main = $('tr.main');
            var sub = $("tr.sub");
            var drop = $("tr.main a.drop");
            var single = $('tr.single');
            $('td a').click(function(e) {
                e.preventDefault();
            })

            drop.click(function() {
                $(this).parent().parent().parent().find('.sub').toggleClass('display');
                $(this).parent().parent().parent().find('.single').addClass('display');
                // $(this).parent().parent().parent().find('.subinside').addClass('display');
                $('i.main').toggleClass('fa-folder');
                $('i.main').toggleClass('fa-folder-open');
            })
            $('.sub-drop').click(function() {
                $(this).parent().parent().parent().find('.single').toggleClass('display');
                $(this).parent().parent().parent().find('.subinside').toggleClass('display');
                $('i.subicon').toggleClass('fa-folder');
                $('i.subicon').toggleClass('fa-folder-open');
            })

            $('.subinside-drop').click(function() {
                $(this).parent().parent().parent().find('.insidesingle').toggleClass('display');
                $('i.subinsideicon').toggleClass('fa-folder');
                $('i.subinsideicon').toggleClass('fa-folder-open');
            })

        })
    </script>
@endpush
