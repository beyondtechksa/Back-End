<template>
    <div v-if="order">
        <div class="modal fade" id="editModal" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="editModalLabel1">Manage Order</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- <button @click="change_status(1)" class="btn btn-danger w-100 mb-2">  Refuse</button>
                <button @click="change_status(2)" class="btn btn-secondary w-100 mb-2"> Accept</button> -->
                <!-- <button @click="change_status(4)" class="btn btn-warning w-100 mb-2">  processing</button> -->
                <button @click="change_status(3)" class="btn btn-warning w-100 mb-2">  in house</button>
                <button @click="change_status(5)" class="btn btn-info w-100 mb-2">  shipping</button>
                <button @click="change_status(6)" class="btn btn-success w-100 mb-2">  delivered</button>
            </div>
           
        </div>
    </div>
</div>
    </div>
</template>


<script>
import Form from 'vform'
    export default {
        props:{
            order:Object,
        },
        data(){
            return{
                vform:new Form({
                    id:null,
                    status:null,
                    
                })
                }
        },
        methods:{
                change_status(status){
                    this.vform.status=status
                    this.vform.id=this.order.id
                    this.vform.post('/shipping/update_order')
                        .then((resp)=>{
                            $('#editModal').modal('hide')
                            this.order.status=resp.data
                        })
                }
            }
    }

</script>