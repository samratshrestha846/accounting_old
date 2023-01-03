<div class="col-md-4">
    <div class="row">
        <div class="col-12">
            <!-- Custom Tabs -->
            <div class="card">
                <div class="card-header d-flex p-2 tex-center">
                    <div class="btn-group text-uppercase" role="group" aria-label="Basic example" id="code_selector">
                        <button type="button" class="btn btn-primary" data-type='1'>Serial No</button>
                        <button type="button" class="btn btn-primary" data-type='3'>Barcode</button>
                        <button type="button" class="btn btn-primary" data-type='2'>Code Scan</button>
                        <button type="button" class="btn btn-primary" data-type='4'>Product Search</button>
                    </div>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Product Code" name="product_barcode"
                            id="product_barcode">
                    </div>
                    <div id="searched-card-items">

                    </div>
                </div><!-- /.card-body -->
            </div>
            <!-- ./card -->
        </div>
        <!-- /.col -->
    </div>

</div>
@push('styles')
    <style>
        #searchProductResults li:hover {
            cursor: pointer;
        }

    </style>
@endpush
@push('scripts')

    <script>
        $(document).ready(function() {

            let reading = false;
            var code_selector = 1;
            var labels = [
                'Product Serial Number',
                'Product Code',
                'Barcode',
                'Product Name Or SKU/Code'
            ];
            const checkActive = function() {
                $('#code_selector button').removeClass('active');
                $(`[data-type=${code_selector}]`).addClass('active');
                $('#product_barcode').attr('placeholder', labels[code_selector - 1]);
            }
            checkActive();


            $('#code_selector button').on('click', function(e) {
                code_selector = $(this).attr('data-type');
                checkActive();

            });

            $('#product_barcode').on('input', function() {
                let url = "{{ route('product-barcode-search') }}";
                let params = {
                    'type': code_selector,
                    'godown': $('#godown').val(),
                    'code': $(this).val(),
                }
                if (params.type == 4) {
                    getSearched();
                    return null;
                }
                if (params.code.length < 5) {
                    return null;
                }
                if (!reading) {
                    reading = true;
                    setTimeout(() => {
                        axios.post(url, params)
                            .then(function(response) {
                                if (response.data.status) {
                                    selectedProductId = response.data.products.id;
                                    $('#addRow').trigger('click');
                                    $('select.item:last').trigger('change');

                                }
                            })
                            .finally(() => {
                                reading = false;
                                $('#product_barcode').val('');
                            });

                    }, 200);
                }

            });
        })

        const getSearched = function() {
            const url = "{{ route('product-name-search') }}";
            let params = {
                'name': $('#product_barcode').val(),
                'godown': $('#godown').val(),
            }
            axios.post(url, params).then((response) => {
                $('#searched-card-items').html(response.data);
            }).finally(() => {
                reading = false;
            });
        }
        $(document).on('click', '#searchProductResults li', function() {
            selectedProductId = $(this).attr('data-productId');
            $('#addRow').trigger('click');
            $('#searched-card-items').html('');
            $('select.item:last').trigger('change');
        })
    </script>
@endpush
