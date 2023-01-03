@extends('backend.layouts.app')
@push('styles')

@endpush
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="sec-header">
            <div class="container-fluid">
                <div class="sec-header-wrap">
                    <h1>View Tax Info </h1>
                    <div class="btn-bulk">
                        <a href="javascript:void(0)" class="global-btn" data-toggle='modal'
                            data-target='#clientadd' data-toggle='tooltip'>Create Tax Info</a>
                    </div>
                    <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (session('success'))
                        <div class="alert  alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                @endif
                @if (session('error'))
                        <div class="alert  alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table text-center global-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap">Fiscal Year</th>
                                        <th class="text-nowrap">Month</th>
                                        <th class="text-nowrap">Purchase Tax</th>
                                        <th class="text-nowrap">Sales Tax</th>
                                        <th class="text-nowrap">Debit Note Tax</th>
                                        <th class="text-nowrap">Credit Note Tax</th>
                                        <th class="text-nowrap">Total Tax</th>
                                        <th class="text-nowrap">Is Paid</th>
                                        <th class="text-nowrap">Previous Due</th>
                                        <th class="text-nowrap">Paid Date</th>
                                        <th class="text-nowrap">Attached File</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($taxinfos as $taxinfo)
                                        <tr>
                                            <td class="text-nowrap">{{ $taxinfo->fiscal_year }}</td>
                                            <td class="text-nowrap">{{ $taxinfo->nep_month }}</td>
                                            <td class="text-nowrap">
                                                Rs.{{ $taxinfo->purchase_tax == null ? '0' : $taxinfo->purchase_tax }}
                                            </td>
                                            <td class="text-nowrap">
                                                Rs.{{ $taxinfo->sales_tax == null ? '0' : $taxinfo->sales_tax }}</td>
                                            <td class="text-nowrap">
                                                Rs.{{ $taxinfo->purchasereturn_tax == null ? '0' : $taxinfo->purchasereturn_tax }}
                                            </td>
                                            <td class="text-nowrap">
                                                Rs.{{ $taxinfo->salesreturn_tax == null ? '0' : $taxinfo->salesreturn_tax }}
                                            </td>
                                            <td class="text-nowrap">Rs.{{ $taxinfo->total_tax }}</td>
                                            <td class="text-nowrap">{{ $taxinfo->is_paid == 0 ? 'Not Paid' : 'Paid' }}
                                            </td>
                                            <td class="text-nowrap">Rs.{{ $taxinfo->due }}</td>
                                            <td class="text-nowrap">
                                                @if ($taxinfo->paid_at == null)
                                                    Not Paid
                                                @else
                                                    {{ $taxinfo->paid_at }}
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                @if ($taxinfo->file == null)
                                                    Not Provided
                                                @else
                                                    <a target="_blank" href="/uploads/{{ $taxinfo->file }}">View File</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="11">No any data.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="text-sm">
                                            Showing <strong>{{ $taxinfos->firstItem() }}</strong> to
                                            <strong>{{ $taxinfos->lastItem() }} </strong> of <strong>
                                                {{ $taxinfos->total() }}</strong>
                                            entries
                                            <span> | Takes <b>{{ round(microtime(true) - LARAVEL_START, 2) }}</b> seconds
                                                to
                                                render</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="pagination-sm m-0 float-right">{{ $taxinfos->links() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
        @if ($taxdetail)
            <div class='modal fade text-left' id='clientadd' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel'
                aria-hidden='true'>
                <div class='modal-dialog' role='document' style="max-width: 1000px;">
                    <div class='modal-content'>
                        <div class='modal-header text-center'>
                            <h2 class='modal-title' id='exampleModalLabel'>Add Paid Tax Infos</h2>
                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                <span aria-hidden='true'>&times;</span>
                            </button>
                        </div>
                        <div class='modal-body'>
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Tax Details</p>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('taxinfo.update', $taxdetail->id) }}" method="POST"
                                        class="bg-light p-3" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="due_tax">Due Tax: </label>
                                                    <input type="text" name="due_tax" class="form-control"
                                                        value="{{ $taxdetail->due }}" placeholder="Due Tax"
                                                        readonly="readonly">
                                                    @error('due_tax')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="paid_at">Paid Date</label>
                                                    <input type="text" name="paid_at" id="entry_date_nepali"
                                                        class="form-control"
                                                        value="{{ App\NepaliCalender\datenep(date('Y-m-d')) }}">
                                                    @error('paid_at')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="file">Associated Documents(PDF)</label>
                                                    <input type="file" name="file" class="form-control">
                                                    @error('file')
                                                        <p class="text-danger">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6"></div>
                                            <input type="radio" name='is_paid' value="1" checked hidden>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('entry_date_nepali').nepaliDatePicker({
            container: '#clientadd'
        });
    </script>
@endpush
