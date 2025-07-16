# What's New in Bag 2.6

## Improvements to Optional Property Validation

Bag 2.6 improves validation of `Optional` properties.

This release changes the behavior of validation when Optionals are involved. Prior to this release, if your validation for an Optional field was a simple presence requirement (e.g. `required`, `present`), an Optional value would pass, whereas a type/format validator would fail.

After this change, **unless** you use the `OptionalOr` validator, Optional values are stripped from the validated values, allowing rules like `required` to fail as expected.

This is a minor backward incompatible change, but the behavior is now what would have been expected previously.

Full documentation on `Optional` and the validation of Optionals can be found [here](/optionals)
