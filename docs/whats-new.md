# What's New in Bag 2.1

## Stripping Extra Parameters

Version 2.1 adds support for stripping extra parameters when creating Bag instances to avoid errors.

Prior to 2.1, Bag would throw an exception if you passed in unknown parameters.

To solve this, the addition of the `\Bag\Attributes\StripExtraParameters` attribute can be used, either [on the
class](./basic-usage#stripping-extra-parameters) or when using the [Laravel controller injection feature](./laravel-controller-injection#avoiding-extra-parameters).

In addition to explicitly opting into this feature, using the [`\Bag\Attributes\WithoutValidation` attribute](./laravel-controller-injection#manual-validation) 
or [`Bag::withoutValidation()`](./validation#creating-a-bag-without-validation) method will also strip extra parameters.
