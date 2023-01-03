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
            @php
                for($x = 0; $x<$quantity; $x++){

                    $code = \DNS1D::getBarcodePNG($product->secondary_code, 'C39', 1.5, 45);
                    echo "
                    <div class='col-md-4'>
                        <div class='text-center'>
                            <h5 class='bold'>$product->product_name</h5>
                            <center>
                                <div> <img src='data:image/png;base64,$code' alt='images'> </div>
                            </center>
                            <p class='bold'>Rs.$product->secondary_unit_selling_price</p>
                        </div>
                    </div>
                    ";
                }
            @endphp

        </div>
    </div>


</body>
</html>
