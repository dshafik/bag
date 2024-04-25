import {defineConfig, HeadConfig } from 'vitepress'

const BASE_PATH = '/bag/'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  title: "Bag",
  description: "Immutable Value Objects for PHP 8.3+",
  base: BASE_PATH,
  head: [
    [
      'meta',
      { name: 'author', content: 'Davey Shafik' }
    ],
    [
      'meta',
      { name:"twitter:image", content: BASE_PATH + "assets/images/social.png" }
    ],
    [
      'meta',
      { name:"og:image", content: BASE_PATH +  "assets/images/social.png" }
    ],
    [
      'link',
      { rel: 'preconnect', href: 'https://fonts.googleapis.com' }
    ],
    [
      'link',
      { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' }
    ],
    [
      'link',
      { href: 'https://fonts.googleapis.com/css2?family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap', rel: 'stylesheet' }
    ],
  ],
  themeConfig: {
    logo: {
      light: '/assets/images/icon-black.png',
      dark: '/assets/images/icon-white.png'
    },
    search: {
      provider: 'local',
    },
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Get Started', link: '/install' }
    ],

    sidebar: [
      {
        text: 'Get Started',
        items: [
          { text: 'Installation', link: '/install' },
          { text: 'Basic Usage', link: '/basic-usage' },
        ]
      },
      {
        text: 'Using Bag',
        items: [
          { text: 'Collections', link: '/collections' },
          { text: 'Casting Values', link: '/casting' },
          { text: 'Validation', link: '/validation' },
          { text: 'Testing', link: '/testing' },
        ]
      },
      {
        text: 'Other',
        items: [
          { text: 'Laravel Controller Injection', link: '/laravel-injection' },
        ]
      },
    ],

    footer: {
      message: 'Made with 🦁💖🏳️‍🌈 by Davey Shafik',
      copyright: "Released under the MIT License. Copyright © 2024 Davey Shafik.",
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/dshafik/bag' }
    ]
  },
  markdown: {
    theme: {
      dark: 'monokai',
      light: 'github-light'
    }
  },
  transformHead: function(context): HeadConfig[]  {
    let head = []
    head.push([
      'meta',
      {
        name: 'author',
        content: 'Davey Shafik'
      }
    ])
    head.push([
      'meta',
      {
        name: 'og:image',
        content: '/assets/images/social.png'
      }
    ])
    head.push([
      'meta',
      {
        name: 'twitter:image',
        content: '/assets/images/social.png'
      }
    ])
  }
})
