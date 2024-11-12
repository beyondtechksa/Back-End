<template>
    <div>

        <auth-layout>
            <div class="container-fluid">
              
                <page-header></page-header>
                
                <InvoiceBody :bill="bill" />
            </div>
        </auth-layout>
    </div>
</template>


<script>

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm} from '@inertiajs/vue3';
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';
import {Form} from 'vform';
import ChangeStatus from './ChangeStatus.vue';
import InvoiceBody from './InvoiceBody.vue';

export default {
    components: {AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, ChangeStatus, InvoiceBody},
    props: {
        bill: Object
    },
    data() {
        return {
            form: useForm({
                search: '',
            }),
            vform: new Form({
                id: null,
                status: null
            }),
            item: null,
            checked: new Array
        }
    },
    methods: {
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
        }
    },
    computed: {
        checkAll: {

            get: function () {
                return this.bills.data ? this.checked.length == this.bills.data.length : false;
            },
            set: function (value) {
                var checked = new Array;
                if (value) {
                    this.bills.data.forEach(function (user) {
                        checked.push(user.id);
                    });
                }
                this.checked = checked;
            }
        }
    }

}

</script>
