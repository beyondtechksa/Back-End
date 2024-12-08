

<template>

    <Head :title="$page.props.page_title" />
      <app>
        <header class="category-header">
      <div class="pt-5">
        <div class="shop-header-container">
          <div class="categories-content">
            <div class="d-flex align-items-center gap-2">
              <div class="categories-list">
                <ul>
                  <li @click="get_children(category)" v-for="category,index in categories" :key="index">
                    <a href="javascript:void(0)" class="active">{{category.translated_name}}</a>
                  </li>

                </ul>
              </div>
            </div>
          </div>

        </div>
        <!-- <form class="d-flex px-3" role="search">
          <div class="search-input d-flex align-items-center gap-2">
            <img src="/home/img/search.svg" alt="" class="">
            <input class="" type="search" placeholder="What are you looking for ?" aria-label="Search">
          </div>
        </form> -->
      </div>
      <!-- ===== Mobile Bootom Menu === -->


    </header>

    <main class="category overflow-hidden">
      <div class="cateorytab">
        <div class="row">
          <div class=" col-md-3  nav-pills tab-btn-category col-4 pe-0" >
            <button @click="select_child(child)" v-for="child,index in children" :key="index" class="nav-link mt-2" :class="{'active':selected_child.id==child.id}" type="button">
              {{child.translated_name}}
            </button>
          </div>
          <div class="col-8 col-md-9 tab-content pe-4" id="v-pills-tabContent">
            <div >
              <h2 class="fs-5 mt-3 ps-1 pb-2">Picks for you</h2>
              <div class="row catagory-rows">
                <div class="col-lg-3 col-md-3 col-4 ps-1 pe-1" v-if="children2.length==0">
                  <category-circle-box v-if="selected_child" :category="selected_child"></category-circle-box>
                </div>
                <div v-show="child2.status" class="col-lg-3 col-md-3 col-4 ps-1 pe-1" v-for="child2,index in children2" :key="index">
                  <category-circle-box  :category="child2"></category-circle-box>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </main>
      </app>

  </template>



  <script>
  import App from '@/HomeLayouts/AppLayout.vue';
import CategoryCircleBox from './Components/CategoryCircleBox.vue';

  export default {
      components: { App, CategoryCircleBox },
      props:{
        categories:Array
      },
      mounted(){
        if(this.categories.length>0){
            this.get_children(this.categories[0])
            if(this.children.length>0){
                this.select_child(this.children[0])
            }
        }
      },
      data(){
        return {
            children:[],
            children2:[],
            selected_parent:{},
            selected_child:{},

        }
      },
      methods:{
        get_children(parent){
            this.selected_parent=parent
            this.children=parent.children
            if(this.children.length>0){
                this.select_child(this.children[0])
            }
        },
        select_child(child){
            this.selected_child=child
            axios.get('/api/get_children/'+child.id)
                 .then((resp)=>{
                    this.children2=resp.data
                 })
        }
      }
  }

  </script>



