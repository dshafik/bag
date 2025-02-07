import taskLists from "markdown-it-task-lists";
import defineVersionedConfig from "vitepress-versioning-plugin";
import {withMermaid} from "vitepress-plugin-mermaid";

const BASE_PATH = '/'

// https://vitepress.dev/reference/site-config
const defaultSidebar = [
  {
    "text": "Get Started",
    "items": [
      { "text": "Installation", "link": "./install" },
      { "text": "Basic Usage", "link": "./basic-usage" }
    ]
  },
  {
    "text": "Using Bag",
    "items": [
      { "text": "Collections", "link": "./collections" },
      { "text": "Casting Values", "link": "./casting" },
      { "text": "Mapping", "link": "./mapping" },
      { "text": "Variadics", "link": "./variadics" },
      { "text": "Hiding Properties", "link": "./hidden" },
      { "text": "Transformers", "link": "./transformers" },
      { "text": "Validation", "link": "./validation" },
      { "text": "Computed Properties", "link": "./computed-properties" },
      { "text": "Output", "link": "./output" },
      { "text": "Wrapping", "link": "./wrapping" },
      { "text": "Factories / Testing", "link": "./testing" }
    ]
  },
  {
    "text": "Laravel Integration",
    "items": [
      { "text": "Controller Injection", "link": "./laravel-controller-injection" },
      { "text": "Route Parameter Binding", "link": "./laravel-route-parameter-binding" },
      { "text": "Eloquent Casting", "link": "./laravel-eloquent-casting" },
      { "text": "Generating Bag Classes", "link": "./laravel-artisan-make-bag-command" }
    ]
  },
  {
    "text": "Other",
    "items": [
      { "text": "Creating Bags from Objects", "link": "./object-to-bag" },
      { "text": "Why Bag?", "link": "./why" },
      { "text": "How Bag Works", "link": "./how-bag-works" },
    ]
  }
];
export default withMermaid(defineVersionedConfig({
  title: "Bag",
  description: "Immutable Value Objects for PHP 8.3+",
  base: BASE_PATH,
  versioning: {
    latestVersion: '2.1',
  },
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
      options: {
        locales: {
          "root": {
             translations: {
               button: {
                 buttonText: "Search latest version…"
               }
             }
          }
        },
        async _render(src, env, md) {
          const html = md.render(src, env)
          if (env.frontmatter?.search === false) return ''
          if (env.relativePath.match(/\d+\.(\d+|x)/) !== null) return ''
          return html
        }
      },
    },
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: './' },
      { text: 'Documentation', link: './install' },
      {
        component: 'VersionSwitcher',
      }
    ],

    sidebar: {
      "/": [
          ...defaultSidebar,
        {"text": "What's New", "link": "./whats-new"},
        {"text": "Upgrading to Bag 2", "link": "./upgrading"}
      ],
      "/2.0/": [
          ...defaultSidebar,
        {"text": "Upgrading to Bag 2", "link": "./upgrading"}
      ],
      "/1.x/": defaultSidebar,
    },

    footer: {
      message: "Made with 🦁💖🏳️‍🌈 by <a href=\"https://www.daveyshafik.com\">Davey Shafik</a>.",
      copyright: "Released under the MIT License. Copyright © 2024 Davey Shafik.",
    },

    socialLinks: [
      { icon: 'github', link: 'https://github.com/dshafik/bag' }
    ],

    versionSwitcher: false,
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
}, __dirname))
