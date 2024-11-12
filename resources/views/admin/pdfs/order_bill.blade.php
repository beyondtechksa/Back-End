<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Design with Bootstrap</title>
    <!-- Bootstrap Css -->
    <link id="style" href="/assets/libs/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <!-- Style Css -->
    <link href="/assets/css/styles.min.css" rel="stylesheet" >
    
    <!-- Icons Css -->
    <link href="/assets/css/icons.css" rel="stylesheet" >
    <style>
    
        body {
            background: #fff;
            margin: 0; /* Remove default margin */
        }
        .pdf-container{padding-left:50px;padding-right: 50px ; width: 794px;margin: 0 auto }
        .logo{width:150px;margin:0 auto}
    </style>
</head>
<body>

    <div id="pdf">
        <div class="pdf-container py-3">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="text-center">
                        <img class="logo" src="/assets/images/logo.png">    
                    </div>
                    <div class="row gy-3">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                    <p class="mb-2">
                                        Billing From :
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> company name: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->name}} </span>
                                       @else
                                       <span> {{$bill->user_name}} </span>
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> company address: </span>
                                        @if(is_array($bill->order->address))
                                        {{$bill->order->address['details']}}
                                        @else 
                                        {{$bill->order->address}}
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> company phone: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->phone}} </span>
                                       @else
                                       <span> {{$bill->user_phone}} </span>
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                    <span class="fw-bold"> company email: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->email}} </span>
                                       @else
                                       <span> {{$bill->user_email}} </span>
                                        @endif
                                    </p>
                                
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
                                    <p class="mb-2">
                                        Billing To :
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> client name: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->name}} </span>
                                       @else
                                       <span> {{$bill->user_name}} </span>
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> client address: </span>
                                        {{$bill->order->address['details']}}
                                    </p>
                                    <p class="mb-1">
                                        <span class="fw-bold"> client phone: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->phone}} </span>
                                       @else
                                       <span> {{$bill->user_phone}} </span>
                                        @endif
                                    </p>
                                    <p class="mb-1">
                                    <span class="fw-bold"> client email: </span>
                                        @if($bill->user)
                                       <span> {{$bill->user->email}} </span>
                                       @else
                                       <span> {{$bill->user_email}} </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <p class="fw-semibold text-muted mb-1">Invoice ID :</p>
                            <p class="fs-15 mb-1">#{{$bill->bill_id}}</p>
                        </div>
                        <div class="col-xl-3">
                            <p class="fw-semibold text-muted mb-1">Order ID :</p>
                            <p class="fs-15 mb-1">#{{$bill->order_id}}</p>
                        </div>
                        
                        <div class="col-xl-3">
                            <p class="fw-semibold text-muted mb-1">Due Date :</p>
                            <p class="fs-15 mb-1"> {{$bill->created_at->format('d, M Y')}} </p>
                        </div>
                        <div class="col-xl-3">
                            <p class="fw-semibold text-muted mb-1">Due Amount :</p>
                            <p class="fs-16 mb-1 fw-semibold"> {{$bill->order->currency}} {{$bill->order->total_amount}} </p>
                        </div>
                        <div class="col-xl-12">
                            <p class="fw-semibold mb-1">Paid Status :  
                               @if($bill->paid_status)
                                <span  class="text-success"> Paid </span> 
                                @else
                                <span  class="text-danger"> Un Paid </span> 
                                @endif
                            </p>
                        </div>
                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table class="table  border mt-4">
                                    <thead>
                                        <tr>
                                            <th>Product Id</th>
                                            <th>Product Name</th>
                                            <th>QUANTITY</th>
                                            <th>PRICE PER UNIT</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bill->order->order_items as $order_item)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{$order_item->product->id}}
                                                </div>
                                            </td>
                                            <td>
                                                <div style="width:100px">
                                                   {{$order_item->product->name_en}}
                                                </div>
                                            </td>
                                            <td class="product-quantity-container">
                                                {{$order_item->quantity}}
                                            </td>
                                            <td>
                                                {{$bill->order->currency}} {{$order_item->price}}
                                            </td>
                                            <td>
                                                {{$bill->order->currency}} {{$order_item->price * $order_item->quantity}}
                                            </td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">
                                                <table class="table table-sm text-nowrap mb-0 table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">Sub Total :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">{{$bill->order->currency}} {{$bill->order->subtotal_amount}}</p>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">Coupon Discount :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">{{$bill->order->currency}} <span class="text-success">{{$bill->order->discount}} </span></p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">Vat  :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">{{$bill->order->currency}} <span class="text-danger"> {{$bill->order->vat}} </span></p>
                                                            </td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0 fs-14">Total :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-16 text-success">{{$bill->order->currency}} {{$bill->order->total_amount}}</p>
                                                            </td>
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
                    <div class="row mt-3 pt-2">
                        <div class="col-xl-4">
                            <p class="fw-semibold mb-1">Store Address :</p>
                            <p class="fs-13 text-muted mb-1"> Sudia Arabia Riad Yormouk  </p>
                        </div>
                        <div class="col-xl-4">
                            <p class="fw-semibold mb-1">Tax ID :</p>
                            <p class="fs-13 text-muted mb-1">12983721</p>
                        </div>
                        
                        <div class="col-xl-4">
                            <p class="fw-semibold mb-1">comercial history :</p>
                            <p class="fs-13 text-muted mb-1"> 1237816293 </p>
                        </div>
                        
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    {{-- <div class="pdf-container py-3"> --}}
        
    {{-- </div> --}}



    <script src="https://rawgit.com/eKoopmans/html2pdf/master/dist/html2pdf.bundle.js"></script>
    <script>
        function downloadPDF() {
            var element = document.getElementById('pdf');
            html2pdf(element, {
                // margin: 20, // Set a default margin for all pages
                filename: 'bill.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98 // Higher quality for images
                },
                html2canvas: {
                    scale: 4, // Higher scale for better resolution
                    useCORS: true, // Allow cross-origin images
                    dpi: 300, // High DPI for better text clarity
                    letterRendering: true // Improves text rendering quality
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4',
                    orientation: 'portrait',
                    precision: 16 // Higher precision for better quality
                }
           
            });
        }



        downloadPDF()
        const button = document.getElementById('cmd');
        button.addEventListener('click', function() {
            downloadPDF()
            });
    </script>


    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
