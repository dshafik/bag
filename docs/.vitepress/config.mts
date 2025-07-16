import taskLists from "markdown-it-task-lists";
import defineVersionedConfig from "vitepress-versioning-plugin";
import {withMermaid} from "vitepress-plugin-mermaid";

const BASE_PATH = '/'

// https://vitepress.dev/reference/site-config
export default withMermaid(defineVersionedConfig({
  title: "Bag",
  description: "Immutable Value Objects for PHP 8.3+",
  base: BASE_PATH,
  versioning: {
    latestVersion: '2.6',
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
      { text: 'What\'s New', link: './whats-new' },
      { text: 'Documentation', link: './install' },
      {
        component: 'VersionSwitcher',
      }
    ],

    sidebar: {
      "/": [
        {
          "text": "Get Started",
          "items": [
            {"text": "Installation", "link": "/install"},
            {"text": "Basic Usage", "link": "/basic-usage"}
          ]
        },
        {
          "text": "Using Bag",
          "items": [
            {"text": "Collections", "link": "/collections"},
            {"text": "Casting Values", "link": "/casting"},
            {"text": "Mapping", "link": "/mapping"},
            {"text": "Variadics", "link": "/variadics"},
            {"text": "Hiding Properties", "link": "/hidden"},
            {"text": "Transformers", "link": "/transformers"},
            {"text": "Optionals", "link": "/optionals"},
            {"text": "Validation", "link": "/validation"},
            {"text": "Computed Properties", "link": "/computed-properties"},
            {"text": "Output", "link": "/output"},
            {"text": "Wrapping", "link": "/wrapping"},
            {"text": "Factories / Testing", "link": "/testing"},
            {"text": "TypeScript", "link": "/typescript"},
          ]
        },
        {
          "text": "Laravel Integration",
          "items": [
            {"text": "Controller Injection", "link": "/laravel-controller-injection"},
            {"text": "Route Parameter Binding", "link": "/laravel-route-parameter-binding"},
            {"text": "Eloquent Casting", "link": "/laravel-eloquent-casting"},
            {"text": "Generating Bag Classes", "link": "/laravel-artisan-make-bag-command"},
            {"text": "Laravel Debugbar Integration", "link": "/laravel-debugbar"}
          ]
        },
        {
          "text": "Other",
          "items": [
            {"text": "Creating Bags from Objects", "link": "/object-to-bag"},
            {"text": "Why Bag?", "link": "/why"},
            {"text": "How Bag Works", "link": "/how-bag-works"},
          ]
        },
        {"text": "What's New", "link": "/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/upgrading"}
      ],
      "/2.5/": [
        {
          "text": "Get Started",
          "items": [
            {"text": "Installation", "link": "/2.4/install"},
            {"text": "Basic Usage", "link": "/2.4/basic-usage"}
          ]
        },
        {
          "text": "Using Bag",
          "items": [
            {"text": "Collections", "link": "/2.4/collections"},
            {"text": "Casting Values", "link": "/2.4/casting"},
            {"text": "Mapping", "link": "/2.4/mapping"},
            {"text": "Variadics", "link": "/2.4/variadics"},
            {"text": "Hiding Properties", "link": "/2.4/hidden"},
            {"text": "Transformers", "link": "/2.4/transformers"},
            {"text": "Validation", "link": "/2.4/validation"},
            {"text": "Computed Properties", "link": "/2.4/computed-properties"},
            {"text": "Output", "link": "/2.4/output"},
            {"text": "Wrapping", "link": "/2.4/wrapping"},
            {"text": "Factories/2.3/ Testing", "link": "/2.4/testing"}
          ]
        },
        {
          "text": "Laravel Integration",
          "items": [
            {"text": "Controller Injection", "link": "/2.4/laravel-controller-injection"},
            {"text": "Route Parameter Binding", "link": "/2.4/laravel-route-parameter-binding"},
            {"text": "Eloquent Casting", "link": "/2.4/laravel-eloquent-casting"},
            {"text": "Generating Bag Classes", "link": "/2.4/laravel-artisan-make-bag-command"},
          ]
        },
        {
          "text": "Other",
          "items": [
            {"text": "Creating Bags from Objects", "link": "/2.4/object-to-bag"},
            {"text": "Why Bag?", "link": "/2.4/why"},
            {"text": "How Bag Works", "link": "/2.4/how-bag-works"},
          ]
        },
        {"text": "What's New", "link": "/2.4/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.4/upgrading"}
      ],
      "/2.4/": [
          {
            "text": "Get Started",
            "items": [
              {"text": "Installation", "link": "/2.4/install"},
              {"text": "Basic Usage", "link": "/2.4/basic-usage"}
            ]
          },
          {
            "text": "Using Bag",
            "items": [
              {"text": "Collections", "link": "/2.4/collections"},
              {"text": "Casting Values", "link": "/2.4/casting"},
              {"text": "Mapping", "link": "/2.4/mapping"},
              {"text": "Variadics", "link": "/2.4/variadics"},
              {"text": "Hiding Properties", "link": "/2.4/hidden"},
              {"text": "Transformers", "link": "/2.4/transformers"},
              {"text": "Validation", "link": "/2.4/validation"},
              {"text": "Computed Properties", "link": "/2.4/computed-properties"},
              {"text": "Output", "link": "/2.4/output"},
              {"text": "Wrapping", "link": "/2.4/wrapping"},
              {"text": "Factories/2.3/ Testing", "link": "/2.4/testing"}
            ]
          },
          {
            "text": "Laravel Integration",
            "items": [
              {"text": "Controller Injection", "link": "/2.4/laravel-controller-injection"},
              {"text": "Route Parameter Binding", "link": "/2.4/laravel-route-parameter-binding"},
              {"text": "Eloquent Casting", "link": "/2.4/laravel-eloquent-casting"},
              {"text": "Generating Bag Classes", "link": "/2.4/laravel-artisan-make-bag-command"},
            ]
          },
          {
            "text": "Other",
            "items": [
              {"text": "Creating Bags from Objects", "link": "/2.4/object-to-bag"},
              {"text": "Why Bag?", "link": "/2.4/why"},
              {"text": "How Bag Works", "link": "/2.4/how-bag-works"},
            ]
          },
        {"text": "What's New", "link": "/2.4/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.4/upgrading"}
      ],
      "/2.3/": [
          {
            "text": "Get Started",
            "items": [
              {"text": "Installation", "link": "/2.3/install"},
              {"text": "Basic Usage", "link": "/2.3/basic-usage"}
            ]
          },
          {
            "text": "Using Bag",
            "items": [
              {"text": "Collections", "link": "/2.3/collections"},
              {"text": "Casting Values", "link": "/2.3/casting"},
              {"text": "Mapping", "link": "/2.3/mapping"},
              {"text": "Variadics", "link": "/2.3/variadics"},
              {"text": "Hiding Properties", "link": "/2.3/hidden"},
              {"text": "Transformers", "link": "/2.3/transformers"},
              {"text": "Validation", "link": "/2.3/validation"},
              {"text": "Computed Properties", "link": "/2.3/computed-properties"},
              {"text": "Output", "link": "/2.3/output"},
              {"text": "Wrapping", "link": "/2.3/wrapping"},
              {"text": "Factories/2.3/ Testing", "link": "/2.3/testing"}
            ]
          },
          {
            "text": "Laravel Integration",
            "items": [
              {"text": "Controller Injection", "link": "/2.3/laravel-controller-injection"},
              {"text": "Route Parameter Binding", "link": "/2.3/laravel-route-parameter-binding"},
              {"text": "Eloquent Casting", "link": "/2.3/laravel-eloquent-casting"},
              {"text": "Generating Bag Classes", "link": "/2.3/laravel-artisan-make-bag-command"},
            ]
          },
          {
            "text": "Other",
            "items": [
              {"text": "Creating Bags from Objects", "link": "/2.3/object-to-bag"},
              {"text": "Why Bag?", "link": "/2.3/why"},
              {"text": "How Bag Works", "link": "/2.3/how-bag-works"},
            ]
          },
        {"text": "What's New", "link": "/2.3/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.3/upgrading"}
      ],
      "/2.2/": [
          {
            "text": "Get Started",
            "items": [
              {"text": "Installation", "link": "/2.2/install"},
              {"text": "Basic Usage", "link": "/2.2/basic-usage"}
            ]
          },
          {
            "text": "Using Bag",
            "items": [
              {"text": "Collections", "link": "/2.2/collections"},
              {"text": "Casting Values", "link": "/2.2/casting"},
              {"text": "Mapping", "link": "/2.2/mapping"},
              {"text": "Variadics", "link": "/2.2/variadics"},
              {"text": "Hiding Properties", "link": "/2.2/hidden"},
              {"text": "Transformers", "link": "/2.2/transformers"},
              {"text": "Validation", "link": "/2.2/validation"},
              {"text": "Computed Properties", "link": "/2.2/computed-properties"},
              {"text": "Output", "link": "/2.2/output"},
              {"text": "Wrapping", "link": "/2.2/wrapping"},
              {"text": "Factories/2.2/ Testing", "link": "/2.2/testing"}
            ]
          },
          {
            "text": "Laravel Integration",
            "items": [
              {"text": "Controller Injection", "link": "/2.2/laravel-controller-injection"},
              {"text": "Route Parameter Binding", "link": "/2.2/laravel-route-parameter-binding"},
              {"text": "Eloquent Casting", "link": "/2.2/laravel-eloquent-casting"},
              {"text": "Generating Bag Classes", "link": "/2.2/laravel-artisan-make-bag-command"},
            ]
          },
          {
            "text": "Other",
            "items": [
              {"text": "Creating Bags from Objects", "link": "/2.2/object-to-bag"},
              {"text": "Why Bag?", "link": "/2.2/why"},
              {"text": "How Bag Works", "link": "/2.2/how-bag-works"},
            ]
          },
        {"text": "What's New", "link": "/2.2/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.2/upgrading"}
      ],
      "/2.1/": [
          {
            "text": "Get Started",
            "items": [
              {"text": "Installation", "link": "/2.1/install"},
              {"text": "Basic Usage", "link": "/2.1/basic-usage"}
            ]
          },
          {
            "text": "Using Bag",
            "items": [
              {"text": "Collections", "link": "/2.1/collections"},
              {"text": "Casting Values", "link": "/2.1/casting"},
              {"text": "Mapping", "link": "/2.1/mapping"},
              {"text": "Variadics", "link": "/2.1/variadics"},
              {"text": "Hiding Properties", "link": "/2.1/hidden"},
              {"text": "Transformers", "link": "/2.1/transformers"},
              {"text": "Validation", "link": "/2.1/validation"},
              {"text": "Computed Properties", "link": "/2.1/computed-properties"},
              {"text": "Output", "link": "/2.1/output"},
              {"text": "Wrapping", "link": "/2.1/wrapping"},
              {"text": "Factories/2.1/ Testing", "link": "/2.1/testing"}
            ]
          },
          {
            "text": "Laravel Integration",
            "items": [
              {"text": "Controller Injection", "link": "/2.1/laravel-controller-injection"},
              {"text": "Route Parameter Binding", "link": "/2.1/laravel-route-parameter-binding"},
              {"text": "Eloquent Casting", "link": "/2.1/laravel-eloquent-casting"},
              {"text": "Generating Bag Classes", "link": "/2.1/laravel-artisan-make-bag-command"},
            ]
          },
          {
            "text": "Other",
            "items": [
              {"text": "Creating Bags from Objects", "link": "/2.1/object-to-bag"},
              {"text": "Why Bag?", "link": "/2.1/why"},
              {"text": "How Bag Works", "link": "/2.1/how-bag-works"},
            ]
          },
        {"text": "What's New", "link": "/2.1/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.1/upgrading"}
      ],
      "/2.0/": [
          {
            "text": "Get Started",
            "items": [
              {"text": "Installation", "link": "/2.0/install"},
              {"text": "Basic Usage", "link": "/2.0/basic-usage"}
            ]
          },
          {
            "text": "Using Bag",
            "items": [
              {"text": "Collections", "link": "/2.0/collections"},
              {"text": "Casting Values", "link": "/2.0/casting"},
              {"text": "Mapping", "link": "/2.0/mapping"},
              {"text": "Variadics", "link": "/2.0/variadics"},
              {"text": "Hiding Properties", "link": "/2.0/hidden"},
              {"text": "Transformers", "link": "/2.0/transformers"},
              {"text": "Validation", "link": "/2.0/validation"},
              {"text": "Computed Properties", "link": "/2.0/computed-properties"},
              {"text": "Output", "link": "/2.0/output"},
              {"text": "Wrapping", "link": "/2.0/wrapping"},
              {"text": "Factories/2.0/ Testing", "link": "/2.0/testing"}
            ]
          },
          {
            "text": "Laravel Integration",
            "items": [
              {"text": "Controller Injection", "link": "/2.0/laravel-controller-injection"},
              {"text": "Route Parameter Binding", "link": "/2.0/laravel-route-parameter-binding"},
              {"text": "Eloquent Casting", "link": "/2.0/laravel-eloquent-casting"},
              {"text": "Generating Bag Classes", "link": "/2.0/laravel-artisan-make-bag-command"}
            ]
          },
          {
            "text": "Other",
            "items": [
              {"text": "Creating Bags from Objects", "link": "/2.0/object-to-bag"},
              {"text": "Why Bag?", "link": "/2.0/why"},
              {"text": "How Bag Works", "link": "/2.0/how-bag-works"},
            ]
          },
        {"text": "What's New", "link": "/2.0/whats-new"},
        {"text": "Upgrading to Bag 2", "link": "/2.0/upgrading"}
      ],
      "/1.x/": [
        {
          "text": "Get Started",
          "items": [
            {"text": "Installation", "link": "/1.x/install"},
            {"text": "Basic Usage", "link": "/1.x/basic-usage"}
          ]
        },
        {
          "text": "Using Bag",
          "items": [
            {"text": "Collections", "link": "/1.x/collections"},
            {"text": "Casting Values", "link": "/1.x/casting"},
            {"text": "Mapping", "link": "/1.x/mapping"},
            {"text": "Variadics", "link": "/1.x/variadics"},
            {"text": "Hiding Properties", "link": "/1.x/hidden"},
            {"text": "Transformers", "link": "/1.x/transformers"},
            {"text": "Validation", "link": "/1.x/validation"},
            {"text": "Computed Properties", "link": "/1.x/computed-properties"},
            {"text": "Output", "link": "/1.x/output"},
            {"text": "Wrapping", "link": "/1.x/wrapping"},
            {"text": "Factories/1.x/ Testing", "link": "/1.x/testing"}
          ]
        },
        {
          "text": "Laravel Integration",
          "items": [
            {"text": "Controller Injection", "link": "/1.x/laravel-controller-injection"},
            {"text": "Route Parameter Binding", "link": "/1.x/laravel-route-parameter-binding"},
            {"text": "Eloquent Casting", "link": "/1.x/laravel-eloquent-casting"},
            {"text": "Generating Bag Classes", "link": "/1.x/laravel-artisan-make-bag-command"}
          ]
        },
        {
          "text": "Other",
          "items": [
            {"text": "Creating Bags from Objects", "link": "/1.x/object-to-bag"},
            {"text": "Why Bag?", "link": "/1.x/why"},
            {"text": "How Bag Works", "link": "/1.x/how-bag-works"},
          ]
        }
      ],
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
