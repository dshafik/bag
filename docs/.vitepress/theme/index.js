// .vitepress/theme/index.js
import DefaultTheme from 'vitepress/theme-without-fonts'
import './custom.css'
import OldVersionWarning from './OldVersionWarning.vue'
import { h } from 'vue'
import VersionSwitcher from "./VersionSwitcher.vue";

export default {
    extends: DefaultTheme,
    enhanceApp({ app }) {
        app.component('VersionSwitcher', VersionSwitcher)
    },
    Layout() {
        return h(DefaultTheme.Layout, null, {
            'home-hero-before': () => h(OldVersionWarning),
            'doc-before': () => h(OldVersionWarning),
            'not-found': () => h(OldVersionWarning),
        })
    }
}
