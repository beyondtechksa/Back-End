<template>
<div class="modal fade" id="collectionsModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Collections</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li v-for="collection,index in collections" :key="index"
                        class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                        {{collection.translated_name}}
                        <span @click="copy(collection)" class="badge bg-primary cursor-pointer">copy</span>
                    </li>
                
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>

<buttton data-bs-toggle="modal" data-bs-target="#collectionsModal" class="btn btn-primary mb-3"> view collections links </buttton>
            

</template>

<script>
    export default {
        props:{
            collections:Array
        },
        methods:{
            copy(collection){
                var textToCopy = this.$page.props.ziggy.url+'/collections/'+collection.slug;

                // Use the Clipboard API to write the text to the clipboard
                navigator.clipboard.writeText(textToCopy).then(function() {
                    alert('Copied to clipboard: ' + textToCopy);
                }).catch(function(error) {
                    console.error('Failed to copy text: ', error);
                });
            }
        }
    }

</script>