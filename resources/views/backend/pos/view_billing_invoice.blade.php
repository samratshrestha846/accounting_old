@extends('backend.pos.billing.pos_invoice')
@push('styles')
@endpush
@section('content')
<div id="buttons" style="padding-top:10px; text-transform:uppercase;">
    <div class="col">
        <span class="left" style="width:60%;margin-right:10px;">
            <button type="button" id="email" class="btn btn-success">Email</button>
        </span>
        <span class="right" style="width:40%;">
            <a href="{{route('pos.generateinvoicebill', $billing->id)}}" id="printinvoice" class="btn btn-warning">Print</a>
        </span>
    </div>
    <div style="clear:both;"></div>
    <a href="{{route('pos.index')}}" class="btn btn-primary">Back to POS</a>
    <div style="clear:both;"></div>
    <div style="background:#F5F5F5; padding:10px;">
        <p style="font-weight:bold;">Please don't forget to disble the header and footer in browser print settings.</p>
        <p style="text-transform: capitalize;">
            <strong>FF:</strong> File &gt; Print Setup &gt; Margin &amp; Header/Footer Make all --blank--
        </p>
        <p style="text-transform: capitalize;">
            <strong>chrome:</strong> Menu &gt; Print &gt; Disable Header/Footer in Option &amp; Set Margins to None
        </p>
    </div>
    <div style="clear:both;"></div>
</div>
@endsection
@push('scripts')
<script src="{{asset('js/axios.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('#printinvoice').printPage();
        document.getElementById('printinvoice').click();

        $('#email').click( function(){
            var email = prompt("Email Address", "{{$billing->client ? $billing->client->email : ''}}");
            if (email!=null){
                axios.post("{{route('pos.sendEmailBill', $billing->id)}}", {
                    'email': email
                })
                .then(response => {
                    alert("Email Sent Successfully");
                })
                .catch(error => {
                    alert('<em>ajax_request_failed</em>');
                });
            }
            return false;
        });
    });
</script>
@endpush
