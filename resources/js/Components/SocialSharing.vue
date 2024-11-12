<template>
  <div class="share-container">
      <div class="social-icons" :class="{ 'show-icons': iconsVisible }">
        <a :href="facebookShareUrl" target="_blank" rel="noopener noreferrer" class="icon facebook"><i class="fab fa-facebook"></i></a>
        <a :href="twitterShareUrl" target="_blank" rel="noopener noreferrer" class="icon twitter"><i class="fab fa-twitter"></i></a>
        <a :href="linkedInShareUrl" target="_blank" rel="noopener noreferrer" class="icon linkedin"><i class="fab fa-linkedin"></i></a>
        <a :href="whatsappShareUrl" target="_blank" rel="noopener noreferrer" class="icon whatsapp"><i class="fab fa-whatsapp"></i></a>
        <a :href="emailShareUrl" target="_blank" rel="noopener noreferrer" class="icon email"><i class="fa fa-envelope"></i></a>
      </div>
    <button class="share-button" @click="showIcons">
      <i class="fa fa-share-alt"></i>
    </button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      iconsVisible: false
    };
  },
  computed: {
    productUrl() {
      return window.location.href; // Assuming the product page URL is the current URL
    },
    productTitle() {
      return 'Check out this product!'; // Example title
    },
    facebookShareUrl() {
      return `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(this.productUrl)}`;
    },
    twitterShareUrl() {
      return `https://twitter.com/intent/tweet?url=${encodeURIComponent(this.productUrl)}&text=${encodeURIComponent(this.productTitle)}`;
    },
    linkedInShareUrl() {
      return `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(this.productUrl)}`;
    },
    whatsappShareUrl() {
      return `https://wa.me/?text=${encodeURIComponent(this.productUrl)}`;
    },
    emailShareUrl() {
      return `mailto:?subject=${encodeURIComponent('Check out this product!')}&body=${encodeURIComponent(this.productUrl)}`;
    }
  },
  methods: {
    showIcons() {
      this.iconsVisible=!this.iconsVisible;
    },
    
  }
};
</script>

<style scoped>
.share-container{
    display: flex;
    align-items: center;
}
.share-button{padding: 9px 15px;
    background: #ffffff;
    color: #222;
    border: 1px solid #dddd;
    border-radius: 3px;}
.social-icons{display: none;}
.social-icons .icon{padding: 9px 13px;color: #fff;    display: table-cell;}
.icon.facebook{background: #2163b7;}
.icon.twitter{background: #1e8ec5;}
.icon.linkedin{background: #1f3fe3;}
.icon.whatsapp{background: #54c979;}
.icon.email{background: #d11b1b;}
.social-icons.show-icons{display: flex;}

</style>