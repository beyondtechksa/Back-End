import {createI18n} from 'vue-i18n'

import ar from '/resources/lang/ar.json'
import en from '/resources/lang/en.json'


const messages = {
    ar,
    en,
}

const locale = sessionStorage.getItem("locale");

const i18n = createI18n({
    locale: locale || "en",
    fallbackLocale: locale,
    messages: messages
});
export default i18n;
