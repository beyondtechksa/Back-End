<template>
    <div class="pdf-container">
        <div class="card custom-card">
        <button class="btn btn-primary" @click="printDiv"><i class="ri-printer-line"></i> Download </button>
        </div>

    </div>

<div id="pdf">
    <div class="pdf-container">
        <div class="card custom-card">
            <div class="card-body">
                <div class="text-center">
                    <h4 class="pb-0 mb-0"> فاتورة ضريبية </h4>
                    <h4> Tax Invoice  </h4>
                </div>
                <div class="row gy-3">
                    <div class="col-6">
                        <table class="table table-bordered border mb-5">
                            <thead>
                            <tr>
                                <th> Invoic number </th>
                                <th> {{ bill.bill_id }} </th>
                                <th class="text-end"> {{ bill.bill_id }} </th>
                                <th class="text-end"> رقم الفاتورة </th>
                            </tr>
                            </thead>
                        </table>
                        <table class="table table-bordered border mb-5">
                            <thead>
                            <tr>
                                <th> Invoic Issue Date </th>
                                <th> {{ formateDate(bill.created_at) }} </th>
                                <th class="text-end"> {{ formateDate(bill.created_at) }} </th>
                                <th class="text-end"> تاريخ اصدار الفاتورة </th>
                            </tr>
                            <tr>
                                <th> Date Of Supply </th>
                                <th> {{ formateDate(bill.order.created_at) }} </th>
                                <th class="text-end"> {{ formateDate(bill.order.created_at) }} </th>
                                <th class="text-end"> تاريخ  التوريد </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-6 text-end">
                        <qrcode-vue :render-as="'svg'" :value="$page.props.ziggy.url" :size="150" level="H" />
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr class="text-white">
                            <th> Seller </th>
                            <th class="text-center"></th>
                            <th class="text-end"> المورد </th>
                            <th> Buyer </th>
                            <th class="text-center"></th>
                            <th class="text-end"> العميل </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th> Name </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> الاسم </th>
                            <th> Name </th>
                            <th class="text-center"> {{ bill.user.name }} </th>
                            <th class="text-end"> الاسم </th>
                        </tr>
                        <tr>
                            <th> Building No </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> رقم المبني </th>
                            <th> Building No </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.building_name:null }} </th>
                            <th class="text-end"> رقم المبني </th>
                        </tr>
                        <tr>
                            <th> Street Name </th>
                            <th class="text-center">  </th>
                            <th class="text-end"> اسم لشارع</th>
                            <th> Street Name </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.street:null }} </th>
                            <th class="text-end"> اسم لشارع</th>
                        </tr>
                        <tr>
                            <th> District </th>
                            <th class="text-center">  </th>
                            <th class="text-end"> الحي </th>
                            <th> District </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.neighborhood:null }} </th>
                            <th class="text-end"> الحي </th>
                        </tr>
                        <tr>
                            <th> City </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> المدينة </th>
                            <th> City </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.city:null }} </th>
                            <th class="text-end"> المدينة </th>
                        </tr>
                        <tr>
                            <th> Country </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> البلد </th>
                            <th> Country </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.country:null }} </th>
                            <th class="text-end"> البلد </th>
                        </tr>
                        <tr>
                            <th> Postal Code </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> البلد </th>
                            <th> Postal Code </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.postal_code:null }} </th>
                            <th class="text-end"> البلد </th>
                        </tr>
                        <tr>
                            <th> Postal Code </th>
                            <th class="text-center"> </th>
                            <th class="text-end"> الرمز البريدي </th>
                            <th> Postal Code </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.postal_code:null }} </th>
                            <th class="text-end"> الرمز البريدي </th>
                        </tr>
                        <tr>
                            <th> Additional No </th>
                            <th class="text-center">  </th>
                            <th class="text-end"> الرقم الاضافي للعنوان </th>
                            <th> Additional No </th>
                            <th class="text-center"> {{ bill.order.address?bill.order.address.phone:null }} </th>
                            <th class="text-end"> الرقم الاضافي للعنوان </th>
                        </tr>
                        <tr>
                            <th> Vat Number </th>
                            <th class="text-center">  </th>
                            <th class="text-end">رقم تسجيل ضريبة القيمة المضافة</th>
                            <th> Vat Number </th>
                            <th class="text-center">  </th>
                            <th class="text-end">رقم تسجيل ضريبة القيمة المضافة</th>
                        </tr>
                        <tr>
                            <th> Other ID </th>
                            <th class="text-center">  </th>
                            <th class="text-end">معرف اخر</th>
                            <th> Other ID </th>
                            <th class="text-center">  </th>
                            <th class="text-end"> معرف اخر </th>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-bordered mt-5">
                    <thead class="bg-dark">
                        <tr class="text-white">
                            <th> Nature of Goods  <br> تفاصيل لسلع والخدمات </th>
                            <th> Unit  <br> سعر الوحدة </th>
                            <th> Quantity  <br> الكمية </th>
                            <th> Taxable Amount  <br> المبلغ الخاضع للضريبة </th>
                            <th> Discount  <br> خصومات </th>
                            <th> Tax Rate  <br>  نسبة الضريبة </th>
                            <th> Tax Amount  <br>  مبلغ الضريبة </th>
                            <th> Subtotal (Including Vat)  <br>  المجموع (شامل ضريبة القيمة المضافة)  </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order_item,index in bill.order.order_items" :key="index">
                            <th>Item {{ index+1 }} -  {{ index+1 }} السلعة </th>
                            <th> {{ order_item.unit_price }} SAR </th>
                            <th> {{ order_item.quantity }} </th>
                            <th> {{  order_item.taxable_amount }} SAR </th>
                            <th> 0 </th>
                            <th> {{ order_item.tax_percentage }} </th>
                            <th> {{ order_item.tax_amount }} </th>
                            <th> {{ order_item.price }} </th>

                        </tr>
                        <tr>
                            <td colspan="4"></td>
                            <td colspan="4">
                                <table class="table table-sm text-nowrap mb-0 table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                <p class="mb-0">Total (Excluding Vat) </p>
                                            </th>
                                            <th scope="row">
                                                <p class="mb-0">المجموع (غير شامل  ضريبة القيمة المضافة) </p>
                                            </th>
                                            <td>
                                                <p class="mb-0 fw-semibold fs-15">{{bill.order.currency}} {{bill.order.subtotal_amount}}</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <p class="mb-0">Discount </p>
                                            </th>
                                            <th scope="row">
                                                <p class="mb-0"> مجموع الخصومات </p>
                                            </th>
                                            <td>
                                                <p class="mb-0 fw-semibold fs-15">{{bill.order.currency}} <span class="text-success">{{bill.order.discount}} </span></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <p class="mb-0">Shipping (including vat)</p>
                                            </th>
                                            <th scope="row">
                                                <p class="mb-0">مبلغ الشحن (متضمن القيمة المضافة ) </p>
                                            </th>
                                            <td>
                                                <p class="mb-0 fw-semibold fs-15">{{bill.order.currency}} <span class="text-success">{{bill.order.shipping}} </span></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row">
                                                <p class="mb-0">Total Vat  </p>
                                            </th>
                                            <th scope="row">
                                                <p class="mb-0"> مجموع ضريبة القيمة المضافة   </p>
                                            </th>
                                            <td>
                                                <p class="mb-0 fw-semibold fs-15">{{bill.order.currency}} <span class="text-danger"> {{bill.order.vat}} </span></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th scope="row">
                                                <p class="mb-0 fs-14">Total Amount Due</p>
                                            </th>
                                            <th scope="row">
                                                <p class="mb-0 fs-14"> اجمالي المبلغ المستحق </p>
                                            </th>
                                            <td>
                                                <p class="mb-0 fw-semibold fs-16 text-success">{{bill.order.currency}} {{bill.order.total_amount}}</p>
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
</div>


</template>

<script>
import QrcodeVue from 'qrcode.vue'

    export default {
        props:{
            bill:Object
        },
        components:{QrcodeVue},
        mounted(){

        },
        data(){
            return {

            }
        },
        methods:{
            downloadPDF() {
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
        },
        printDiv() {
            // Get the HTML of the div
            var content = document.getElementById('pdf').innerHTML;

            // Create a new window
            var printWindow = window.open('', '', 'height=600,width=1000');

            var headContent = document.head.innerHTML;

            // Write the HTML to the new window
            printWindow.document.write('<html><head>' + headContent + '</head><body>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(content);
            printWindow.document.write('</body></html>');

            // Close the document to finish loading and start printing
            printWindow.document.close();
            printWindow.print();
        }
        }
    }
</script>

<style scoped>

.pdf-container{padding-left:50px;padding-right: 50px ; width: 100%;margin: 0 auto }
.logo{width:150px;margin:0 auto}
table th,table td{font-size: 10px !important;padding:5px !important}
</style>
