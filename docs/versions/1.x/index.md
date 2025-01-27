---
# https://vitepress.dev/reference/default-theme-home-page
layout: home

hero:
  name: "Bag"
  text: "Immutable Value Objects for PHP"
  tagline: "<code>composer require dshafik/bag</code>"
  image: /assets/images/bag.png
  actions:
    - theme: brand
      text: Get Started
      link: ./install

features:
  - title: Immutable & Strongly typed
    icon: 🦾
    details: Bag value objects are immutable and strongly typed, a safer and more predictable way to work with data.
  - title: Composable
    icon: 🛠
    details: Nest Bag value objects and collections to create complex data structures.
  - title: Built on Laravel
    icon: 📦
    details: Built-in validation, controller dependency injection, and more. Bag is designed to work seamlessly with Laravel.  
---

## What is Bag?

Bag helps you create immutable value objects. It's a great way to encapsulate data within your application.

Bag prioritizes immutability and type safety with built-in validation and data casting.

### When should I use Value Objects?

Value objects should be used in place of regular arrays, allowing you enforce type safety and immutability.

### Does it work with Laravel/Symfony/Other Framework?

Bag is framework-agnostic, but it works great with Laravel. Bag uses standard Laravel [Collections](https://laravel.com/docs/11.x/collections) and [Validation](https://laravel.com/docs/11.x/validation). 
In addition, it will automatically inject `Bag\Bag` value objects into your controllers with validation.

