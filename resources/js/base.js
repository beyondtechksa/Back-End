import {router, useForm} from "@inertiajs/vue3"
import Swal from 'sweetalert2';
import Form from "vform";


export default {
    mounted() {


        const urlParams = new URLSearchParams(window.location.search)
        const myParam = urlParams.get('word')
        if (myParam) {
            window.find(myParam)

        }

        let logo=this.$page.props.settings.find((e)=>e.slug=='logo')
        this.base_logo_setting=logo?logo.value:null
        let favicon=this.$page.props.settings.find((e)=>e.slug=='favicon')
        this.base_favicon_setting=favicon?favicon.value:null


    },

    data() {
        return {
            poll_phone_image:'/assets/images/phone.png',
            base_form:useForm({
                category_id:null,
                product_id:null,
                session_url:null,
                go_to_url:null,
                attributes:[],
                size:null,
                color:null,
            }),
            base_vform:new Form({
                product_id:null,

            }),
            base_errors:{
                color:null,
                size:null,
            },
            base_currencies:[],
            base_logo_setting:null,
            base_favicon_setting:null,
        }
    },
    methods: {
        add_favourite(product) {
            this.base_vform.product_id = product.id
            if (this.$page.props.auth.user) {
                this.base_vform.post(route('product.add_favourite'))
                    .then((resp)=>{
                        let favourites = this.$page.props.auth.user.favourites
                        let product_id=product.id
                        let favourite = favourites.find((e)=>e.product_id==product_id)
                        if(favourite){
                            let index =favourites.indexOf(favourite)
                            favourites.splice(index,1)
                        }else{
                            favourites.push({product_id:product_id})
                        }
                    })
            } else {
                this.login_alert()
            }
        },
        add_cart(product) {
            this.base_form.product_id = product.id
            this.base_errors.color=null
            this.base_errors.size=null
            // if (this.$page.props.auth.user) {
                if(product.colors && product.colors.length>0){
                    if(this.base_form.color==null){
                        this.base_errors.color=this.__('the color is required')
                        return 'ok';
                    }
                }
                if(product.sizes_ids && product.sizes_ids.length>0){
                    if(this.base_form.size==null){
                        this.base_errors.size=this.__('the size is required')
                        return 'ok';
                    }
                }

                this.base_form.post(route('product.add_cart'), {preserveState: false, preserveScroll: true})
            // } else {
            //     this.login_alert()
            // }
        },
        deleteCartItem(product_id){
            this.base_form.product_id = product_id
            this.base_form.post(route('product.deleteCartItem'), {preserveState: false, preserveScroll: true})

        },
        getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        },
        // add_favourite(product) {
        //     this.base_form.product_id = product.id
        //     if (this.$page.props.auth.user) {
        //         this.base_form.post(route('product.add_favourite'), {preserveState: false, preserveScroll: true})
        //     } else {
        //         this.login_alert()
        //     }
        // },
        check_user_cart(product) {
            if (this.$page.props.auth.user) {
                let carts = this.$page.props.auth.user.carts
                if (carts.find((e) => e.product_id == product.id))
                    return true
                else
                    return false
            } else {
                return false
            }
        },
        check_user_favourite(product) {
            if (this.$page.props.auth.user) {
                let favourites = this.$page.props.auth.user.favourites
                if (favourites.find((e) => e.product_id == product.id))
                    return true
                else
                    return false
            } else {
                return false
            }
        },
        login_alert() {
            Swal.fire({
                title: this.__('Login Alert?'),
                text: this.__('You need to login before this action'),
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#390049",
                cancelButtonColor: "#afafaf",
                confirmButtonText: this.__('Yes, Login!'),
            }).then((result) => {
                if (result.value) {
                    router.visit(route('login'))
                }
            });
        },
        getColorName(colors,id) {
            const color = colors.find(c => c.id == id);
            if(color){
                return color.name_tr;
            }else{
                return null
            }
        },

        getSizeName(sizes,id) {
            const size = sizes.find(c => c.id == id);
            if(size){
                return size.name_tr
            }else{
                return null
            }
        },

        /**
         * Translate the given key.
         */
        __(key, replace = {}) {

            var translation = this.$page.props.language[key]
                ? this.$page.props.language[key]
                : key

            Object.keys(replace).forEach(function (key) {
                translation = translation.replace(':' + key, replace[key])
            });

            return translation
        },

        back() {
            window.history.go(-1)
        },
        word_url(url, key) {
            return '/' + url + '?word=' + key
        },
        check_permissions(allowed_permissions) {
            let flag = true
            for (let i = 0; i < allowed_permissions.length; i++) {
                if (this.$page.props.auth.permissions) {
                    if (!this.$page.props.auth.permissions.includes(allowed_permissions[i])) {
                        flag = false;
                    }
                } else {
                    return false
                }
            }
            return flag
        },
        substr(text, length) {
            if (text) {
                if (text.length > length) {
                    return text.substr(0, length) + '..'
                } else {
                    return text
                }
            }
            return text
        },
        formateDate(originalDate) {
            if (originalDate != null) {
                originalDate = new Date(originalDate)
                const day = originalDate.getDate().toString().padStart(2, '0');
                const month = (originalDate.getMonth() + 1).toString().padStart(2, '0');
                const year = originalDate.getFullYear();

                return `${day}-${month}-${year}`;
            } else {
                return null
            }
        },
        get_categories_with_parents(categories_with_parents) {
            let categories = []
            let parents = categories_with_parents
            parents.forEach((el) => {
                let parents_name = el.translated_name
                el['parents_name'] = parents_name
                categories.push(el)
                this.sort_categories(el.children).forEach((el2) => {

                    el2['parents_name'] = parents_name + '/' + el2.translated_name
                    categories.push(el2)
                    this.sort_categories(el2.children).forEach((el3) => {
                        el3['parents_name'] = parents_name + '/' + el2.translated_name + '/' + el3.translated_name
                        categories.push(el3)
                        this.sort_categories(el3.children).forEach((el4) => {
                            el4['parents_name'] = parents_name + '/' + el2.translated_name + '/' + el3.translated_name + '/' + el4.translated_name
                            categories.push(el4)
                        })
                    })
                })
            })

            return categories
        },
        sort_categories(categories){
          return   categories.sort((a,b)=>a.list - b.list)
        },
        go_to_redirect_url(session_url,go_to_url){
            this.base_form.session_url=session_url
            this.base_form.go_to_url=go_to_url
            this.base_form.post('/save_redirect_session')
        },
        exchange_price(price,prefix){
            let currencies = this.$page.props.currencies
            let currency=currencies.find((e)=>e.prefix==prefix)
            let try_currency=currencies.find((e)=>e.prefix=='TRY')
            if(currency){
                return (currency.exchange_rate*price/try_currency.exchange_rate).toFixed(2)
            }else{
                return 'currency not found'
            }
        },
        exchange_price_from_to(price,from,to){
                let currencies = this.$page.props.currencies
                let to_currency=currencies.find((e)=>e.prefix==to)
                let from_currency=currencies.find((e)=>e.prefix==from)
                let try_currency=currencies.find((e)=>e.prefix=='TRY')
                if(to_currency && from_currency){
                    let usd_amount = price / from_currency.exchange_rate
                    let converted = usd_amount * to_currency.exchange_rate
                    return converted.toFixed(2)
                }else{
                    return 'currency not found'
                }

        },
        isObjectWithValue(variable){
            return variable !== null && typeof variable === 'object' && !Array.isArray(variable);
        },
        base_redirect_url(url){
            try {
                const parsedUrl = new URL(url);
                return `${parsedUrl.pathname}${parsedUrl.search}${parsedUrl.hash}`;
              } catch (error) {
                // console.error('Invalid URL:', error);
                return ''; 
              }
        }


    },
    computed:{
        currentLang(){
            return this.$page.props.locale
        }
    }
}
