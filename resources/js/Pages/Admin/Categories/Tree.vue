<template>
  <div>
      <ul id="myUL">
        <draggable v-model="dragged_categories" group="parent"  @end="endDrag()">
          <template  #item="{element:category}">
      <li>
          <span @click="select_category(category)" :class="{'selected':form.category_id==category.id,'caret-down':category.all_children.length>0}" class="caret" >{{category.translated_name}}</span>
          <ul :id="'nested'+category.id" v-if="category.children_count>0" class="nested" >
          <draggable v-model="category.all_children" :group="'parent'+category.id"  @end="endParentDrag(category.all_children)">
          <template  #item="{element:child}">
          <li >
            <span v-if="child.children_count>0" @click="child.all_children=select_child(child)" :class="{'selected':form.category_id==child.id,'caret-down':child.all_children.length>0}" class="caret">{{child.translated_name}}</span>
            <span v-else @click="child.all_children=select_child(child)" :class="{'selected':form.category_id==child.id}">{{child.translated_name}}</span>
              <ul :id="'nested'+child.id" v-if="child.children_count>0" class="nested">
                <draggable v-model="child.all_children" :group="'parent'+child.id"  @end="endParentDrag(child.all_children)">
                <template  #item="{element:child2}">
              <li >
                  <span @click="child2.all_children=select_child(child2)" :class="{'selected':form.category_id==child2.id}"  v-if="child2.children_count==0">  {{child2.translated_name}} </span>
                  <span @click="child2.all_children=select_child(child2)" :class="{'selected':form.category_id==child2.id,'caret-down':child2.all_children.length>0}" class="caret" v-else>  {{child2.translated_name}} </span>
                  <ul :id="'nested'+child2.id" v-if="child2.children_count>0" class="nested">
                    <draggable v-model="child2.all_children" :group="'parent'+child2.id"  @end="endParentDrag(child2.all_children)">
                    <template  #item="{element:child3}">
                      <li> 
                          <span @click="form.category_id=child3.id" :class="{'selected':form.category_id==child3.id}"  v-if="child3.children_count==0">  {{child3.translated_name}} </span>
                          <span @click="form.category_id=child3.id" :class="{'selected':form.category_id==child3.id,'caret-down':child3.all_children.length>0}" class="caret" v-else>  {{child3.translated_name}} </span>
                          <ul :id="'nested'+child3.id" v-if="child3.all_children.length>0" class="nested">
                            <draggable v-model="child3.all_children" :group="'parent'+child3.id"  @end="endParentDrag(child3.all_children)">
                            <template  #item="{element:child4}">
                              <li > 
                                 <span @click="form.category_id=child4.id" :class="{'selected':form.category_id==child4.id}"> {{ child4.translated_name }} </span>
                              </li>
                              </template>
                              </draggable>
                          </ul>
                      </li>
                      </template>
                      </draggable>
                  </ul>
              </li>
              </template>
              </draggable>
             
              </ul>
          </li>  
          </template>
          </draggable>
          </ul>
      </li>
      </template>
      </draggable>
      
    </ul>

  </div>
</template>



<script>
import draggable from 'vuedraggable'
export default {
  components:{
    draggable
  },
  mounted(){
    this.get_main_parents()

      var toggler = document.getElementsByClassName("caret");
      var i;

      for (i = 0; i < toggler.length; i++) {
      toggler[i].addEventListener("click", function() {
          this.parentElement.querySelector(".nested").classList.toggle("active");
          this.classList.toggle("caret-down");
      });
      }
  },
  props:{
      form:Object,
      // categories:Array,
  },
  data(){
    return {
      dragged_categories:[]
    }
  },
  methods:{
    get_main_parents(){
      axios.get('/api/get_main_parents')
            .then((resp)=>{
              resp.data.forEach((el)=>{
                el['all_children']=[]
                this.dragged_categories.push(el)
              })
            })
    },  
    select_category(category){
      this.form.category_id=category.id
      if(category.all_children.length==0){
        axios.get('/api/get_children/'+category.id)
              .then((resp)=>{
                let index = this.dragged_categories.indexOf(category)
                this.dragged_categories[index].all_children=resp.data
                $('.nested').removeClass('active')
                $('#nested'+category.id).addClass('active')
              })
      }else{
        category.all_children=[]
        $('#nested'+category.id).removeClass('active')
      }
    },
    select_child(category){
      this.form.category_id=category.id
      if(category.all_children.length==0){
      axios.get('/api/get_children/'+category.id)
            .then((resp)=>{
              category.all_children=resp.data
              // $('.nested').removeClass('active')
              $('#nested'+category.id).addClass('active')
              
            })
      }else{
        category.all_children=[]
        $('#nested'+category.id).removeClass('active')
      }
      return category.all_children
    },
    endDrag(parent_id=null){
      let requested_categories = this.dragged_categories.filter(item => item.category_id===parent_id)   
      axios.post('/admin/api/update_categories_list',{categories:requested_categories})
    },
    endParentDrag(children){
      axios.post('/admin/api/update_categories_list',{categories:children})
    },
  }
}

</script>

<style>
ul, #myUL {
list-style-type: none;
}

#myUL {
margin: 10px;
padding: 0;
}

.caret {
cursor: pointer;
-webkit-user-select: none; /* Safari 3.1+ */
-moz-user-select: none; /* Firefox 2+ */
-ms-user-select: none; /* IE 10+ */
user-select: none;
}

.caret::before {
content: "\25B6";
color: #5c5c5c;
display: inline-block;
margin-right: 6px;
}

.caret-down::before {
-ms-transform: rotate(90deg); /* IE 9 */
-webkit-transform: rotate(90deg); /* Safari */
transform: rotate(90deg);  
}

.nested {
display: none;
}

.active {
display: block;
}
#myUL li span{display: block;
  cursor: pointer;
  background: #ebebeb;
  margin-top: 10px;
  padding: 10px 15px;}
#myUL li span.selected{color: #845adf;
  font-weight: bold;}
</style>