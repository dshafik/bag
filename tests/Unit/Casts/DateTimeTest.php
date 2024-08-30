<?php

declare(strict_types=1);
use Bag\Casts\DateTime;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;

test('it casts to datetime', function () {
    $cast = new DateTime();

    $datetime = $cast->set(\DateTime::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf(\DateTime::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it casts to datetime immutable', function () {
    $cast = new DateTime();

    $datetime = $cast->set(\DateTimeImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it casts to carbon', function () {
    $cast = new DateTime();

    $datetime = $cast->set(Carbon::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf(Carbon::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it casts to carbon immutable', function () {
    $cast = new DateTime();

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf(CarbonImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it casts to custom', function () {
    $customDateTime = new class () extends CarbonImmutable {};
    $cast = new DateTime(dateTimeClass: $customDateTime::class);

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf($customDateTime::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it does not cast same', function () {
    $cast = new DateTime();

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => new CarbonImmutable('2024-04-30 01:58:23')]));

    expect($datetime)->toBeInstanceOf(CarbonImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it casts incorrect date time interface', function () {
    $cast = new DateTime();

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => new \DateTimeImmutable('2024-04-30 01:58:23')]));

    expect($datetime)->toBeInstanceOf(CarbonImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it enforces strict mode', function () {
    $cast = new DateTime(strictMode: true);

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

    expect($datetime)->toBeInstanceOf(CarbonImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:23');
});

test('it errors in strict mode', function () {
    $this->expectException(InvalidFormatException::class);
    $this->expectExceptionMessage('Not enough data available to satisfy format');

    $cast = new DateTime(strictMode: true);
    $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58']));
});

test('it does not error in non strict mode', function () {
    $cast = new DateTime(strictMode: false);
    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58']));

    expect($datetime)->toBeInstanceOf(CarbonImmutable::class)
        ->and($datetime->format('Y-m-d H:i:s'))->toBe('2024-04-30 01:58:00');
});

test('it parses custom format', function () {
    $cast = new DateTime(format: 'm/d/y');

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '4/30/24']));

    expect($datetime->format('Y-m-d'))->toBe('2024-04-30');
});

test('it formats output using input format by default', function () {
    $cast = new DateTime();
    $output = $cast->get('test', collect(['test' => new CarbonImmutable('2024-04-30 01:58:23')]));

    expect($output)->toBe('2024-04-30 01:58:23');
});

test('it parses and outputs custom format', function () {
    $cast = new DateTime(format: 'm/d/y H:i:s', outputFormat: 'Y-m-d');

    $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '4/30/24 01:58:23']));

    expect($datetime->format('Y-m-d'))->toBe('2024-04-30')
        ->and($cast->get('test', collect(['test' => $datetime])))->toBe('2024-04-30');
});
