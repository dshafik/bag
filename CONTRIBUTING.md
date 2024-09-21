# Contributing to Bag

Bag is an open-source project and we welcome contributions. Whether you're fixing a bug, improving the documentation, or adding a new feature, we appreciate your help.

## Getting Started

To get started, fork the repository and clone it to your local machine. You can find the repository at <https://github.com/dshafik/bag>.

```shell
git clone https://github.com/dshafik/bag.git
```

## Installation

To install the project dependencies, run the following command:

```shell
composer install
```

This will install the required dependencies for the project, and install the git hooks.

## Running Tests

To run the test suite, use the following command:

```shell
composer run test
```

## Coding Standards

Bag follows the [PSR-12 Extended Coding Style standard](https://www.php-fig.org/psr/psr-12/). [Laravel Pint](https://laravel.com/docs/11.x/pint) is used to enforce code style. To check your code for style violations, run the following command:

```shell
composer run style
```

## Commit Standards

All commits **must** adhere to the [Conventional Commits specification](https://www.conventionalcommits.org/en/v1.0.0/).

> [!TIP]
> Pre-commit hooks will automatically run tests, style checks, linting, and validate commit messages.
