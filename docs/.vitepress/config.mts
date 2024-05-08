import { defineConfig } from 'vitepress'
import taskLists from "markdown-it-task-lists";
import { withMermaid } from "vitepress-plugin-mermaid";

const BASE_PATH = '/bag/'

// https://vitepress.dev/reference/site-config
export default withMermaid({
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
          { text: 'Mapping', link: '/mapping' },
          { text: 'Variadics', link: '/variadics' },
          { text: 'Hiding Properties', link: '/hidden' },
          { text: 'Transformers', link: '/transformers' },
          { text: 'Validation', link: '/validation' },
          { text: 'Computed Properties', link: '/computed-properties' },
          { text: 'Wrapping', link: '/wrapping' },
          { text: 'Factories / Testing', link: '/testing' },
        ]
      },
      {
        text: 'Laravel Integration',
        items: [
          { text: 'Controller Injection', link: '/laravel-controller-injection' },
          { text: 'Route Parameter Binding', link: '/laravel-route-parameter-binding' },
          { text: 'Eloquent Casting', link: '/laravel-eloquent-casting' },
        ]
      },
      {
        text: 'Other',
        items: [
          { text: 'Creating Bags from Objects', link: '/object-to-bag' },
          { text: 'Why Bag?', link: '/why' },
          { text: 'How Bag Works', link: '/how-bag-works' },
          { text: 'Roadmap', link: '/roadmap' },
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
    },

    config: md => {
        md.use(taskLists)
    }
  },
})
