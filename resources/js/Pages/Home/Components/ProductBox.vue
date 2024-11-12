<template>
    <Link :href="route('product.show',{id:product.id,slug:product.slug})">
        <div class="product_card">
            <div class="product-content">
            <div class="product_image">
                <img v-lazy="product.image" alt="" />
            </div>
            <div class="product-wishlist">
                <p href="#" class="save"  @click.prevent="add_favourite(product)">
                <img v-if="check_user_favourite(product)" src="/home/img/like.png"  alt="" />
                <img v-else
                    src="/home/img/icon-heart.svg"
                    alt=""
                />
                </p>

            </div>
            <div class="product-offer" v-if="product.discount_percentage_selling_price>0">
                <p href="#" class="offer-btns">
                <img src="/home/img/discount.svg" alt="" />
                <span>{{ product.discount_percentage_selling_price }}</span>
                </p>
            </div>
            </div>
            <div class="product-discription">
            <h3 class="title" v-if="product.brand">{{product.brand.translated_name}}</h3>
            <p class="short-disription">
                {{substr(product['name_'+$page.props.locale],45)}}
            </p>
                <!-- <div class="rattings-start">
                    <ul class="rattings">
                        <li v-for="n in 5" :key="n">
                            <img
                                v-lazy="n <= Math.round(product.rate) ? '/home/img/star-Filled.svg' : '/home/img/star.svg'"
                                alt=""
                            />
                        </li>
                    </ul>
                    <p class="point">{{ product.rate }}/5</p>
                </div> -->
            <div class="product-price pb-2">
                <div class="old-price" v-if="product.discount_percentage_selling_price>0">
                <p> {{exchange_price(product.old_price,'SAR') }} {{__('SAR')}}</p>
                </div>
                <div class="current-price">
                <p> {{exchange_price(product.final_selling_price,'SAR')}} {{__('SAR')}}</p>
                </div>
            </div>
            </div>
        </div>
        </Link>
</template>

<script>

export default {
    props:{product:Object},
    methods:{

    }
}

</script>

<style>

    .save-card.selected{background: #9600c1;}
    .save-card{border: 2px solid transparent;}
    .save-card:hover{border: 2px solid #9600c1;}

</style>
