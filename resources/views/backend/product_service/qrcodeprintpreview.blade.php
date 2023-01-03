<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .row{
            width: 100%;
        }
        .col-md-4{
            width: 33%;
            /* padding:25px; */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">

            @if ($serial_number == null)
                @php
                    for($x = 0; $x<$quantity; $x++){

                        $code = DNS2D::getBarcodeHTML($product->product_code, 'QRCODE');
                        echo "
                        <div class='col-md-4'>
                            <div class='text-center'>
                                <h5 class='bold'>$product->product_name</h5>
                                <center>
                                    <div> <div class='mb-3'>$code</div> </div>
                                </center>
                                <p class='bold'>Rs.$product->product_price</p>
                            </div>
                        </div>
                        ";
                    }
                @endphp
            @elseif($serial_number == 1)
                @foreach($product->godownproduct as $singleGodownProduct)
                    @foreach($singleGodownProduct->serialnumbers as $productSerialNumber)
                        <div class="col-md-4 mt-2">
                            <div class="text-center">
                                <center>
                                    <div class="mb-3">
                                        {!! \DNS2D::getBarcodeHTML($product->product_code.'/'.$productSerialNumber->serial_number, 'QRCODE') !!}
                                    </div>
                                </center>

                                <p class="bold">
                                    Serial Number: {{ $productSerialNumber->serial_number }} <br>
                                    ({{ $singleGodownProduct->godown->godown_name }})
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>
    </div>
</body>
</html>
