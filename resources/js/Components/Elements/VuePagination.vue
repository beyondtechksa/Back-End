<template>
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="fetchData(currentPage= currentPage - 1)">Previous</a>
        </li>
        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ 'active': currentPage === page }" v-show="page<=6 || page==totalPages">
          <a class="page-link" href="#" @click.prevent="fetchData(currentPage=page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
          <a class="page-link" href="#" @click.prevent="fetchData(currentPage=currentPage + 1)">Next</a>
        </li>
      </ul>
    </nav>
  </template>
  
  <script>
  export default {
    props: {
      currentPage: Number,
      totalPages: Number,
      fetchData: Function // Function to fetch data for a given page
    }
  };
  </script>
  
  <style>
  /* Add your pagination styles here */
  </style>