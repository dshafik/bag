# How Bag Works

Bag works by utilizing pipelines to process the input and output data. 

There are four pipelines:

- `InputPipeline` for handling input and constructing the Bag object 
- `ValidationPipeline` for validating without constructing the Bag object
- `OutputPipeline` for handling output
- `OutputCollectionPipeline` for handling collection output

Each pipeline is detailed below.

> [!TIP]
> Each stage in the diagrams below is linked to the relevant source code for that stage.

## The Input Pipeline

The [`InputPipeline`](https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/InputPipeline.php) is responsible for processing the input data so that the `Bag` object can be created. The pipeline
consists of the following steps:

```mermaid
graph TD;
start("Bag::from($data)")
--> transform(Transform Input)
--> process(Process Parameters) 
--> variadic(Is Variadic?)
--> mapInput(Map Input)
-- Finalized Input Values --> missing(Missing Parameters) --> missingError{Error?}
missingError -- Yes --> errorMissingParameters(MissingPropertiesException)
missingError -- No --> extra(Extra Parameters) --> extraError{Error?}
extraError -- Yes --> errorExtraParameters(ExtraPropertiesException)
extraError -- No --> validate(Validate)
--> valid{Valid?}
valid -- No --> errorValidation(ValidationException)
valid -- Yes --> cast(Cast Input)
--> construct("new Bag(...)")
--> computed(Verify Computed Values)
--> initialized{Initialized?}
initialized -- No --> errorInitialization(ComputedPropertyUninitializedException)
initialized -- Yes
--> bag(Bag Value Returned)

class start mermaid-start
class missingError,extraError,valid,initialized mermaid-decision
class bag mermaid-end
class errorMissingParameters,errorExtraParameters,errorValidation,errorInitialization mermaid-error

click start "https://github.com/dshafik/bag/blob/main/src/Bag/Bag.php" _blank
click transform "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/Transform.php" _blank
click process "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ProcessParameters.php" _blank
click variadic "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/IsVariadic.php" _blank
click mapInput "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/MapInput.php" _blank
click missing "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/MissingParameters.php" _blank
click extra "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ExtraParameters.php" _blank
click validate "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/Validate.php" _blank
click cast "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/CastInputValues.php" _blank
click construct "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/FillBag.php" _blank
click computed "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ComputedValues.php" _blank
```

## The Output Pipeline

The `OutputPipeline` is responsible transforming the Bag data to the desired output array or JSON. The pipeline consists of the following steps:

```mermaid
graph TD;
toArray("Bag->toArray()") --> processParameters
toJson("Bag->toJson()") --> processParameters
jsonEncode("json_encode($bag)") --> processParameters
get("Bag->get()") --> processParameters
unwrapped("Bag->unwrapped()") --> processParameters
processParameters(Process Parameters)
--> processProperties(Process Properties)
--> getValues(Get Values)
--> hide("Remove Hidden Attributes*")
--> hideJson("Remove Hidden JSON Attributes*")
--> cast(Cast Output)
--> mapOutput(Map Output)
--> wrap(Wrap Output*)
--> output(array or JSON string)

class toArray,toJson,jsonEncode,get,unwrapped mermaid-start
class output mermaid-end
class hide,hideJson,wrap mermaid-conditional

click toArray "https://github.com/dshafik/bag/blob/main/src/Bag/Concerns/WithArrayable.php" _blank
click toJson "https://github.com/dshafik/bag/blob/main/src/Bag/Concerns/WithJson.php" _blank
click get "https://github.com/dshafik/bag/blob/main/src/Bag/Concerns/WithOutput.php" _blank
click unwrapped "https://github.com/dshafik/bag/blob/main/src/Bag/Concerns/WithOutput.php" _blank
click processParameters "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ProcessParameters.php" _blank
click processProperties "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ProcessProperties.php" _blank
click getValues "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/GetValues.php" _blank
click hide "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/HideValues.php" _blank
click hideJson "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/HideJsonValues.php" _blank
click cast "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/CastOutputValues.php" _blank
click mapOutput "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/MapOutput.php" _blank
click wrap "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/Wrap.php" _blank
```

> [!NOTE]
> \* These steps are only performed if the Bag is being converted to an array and/or JSON.

## The Validation Pipeline

The `ValidationPipeline` is responsible for validating the input data without constructing the Bag object. The pipeline consists of the following steps:

```mermaid
graph TD;
start("Bag::validate($data)")
--> transform(Transform Input)
--> process(Process Parameters) 
--> variadic(Is Variadic?)
--> mapInput(Map Input)
-- Finalized Input Values --> missing(Missing Parameters) --> missingError{Error?}
missingError -- Yes --> errorMissingParameters(MissingPropertiesException)
missingError -- No --> extra(Extra Parameters) --> extraError{Error?}
extraError -- Yes --> errorExtraParameters(ExtraPropertiesException)
extraError -- No --> validate(Validate)
--> valid{Valid?}
valid -- No --> errorValidation(ValidationException)
valid -- Yes --> success(return true)

class start mermaid-start
class missingError,extraError,valid mermaid-decision
class success mermaid-end
class errorMissingParameters,errorExtraParameters,errorValidation mermaid-error

click start "https://github.com/dshafik/bag/blob/main/src/Bag/Concerns/WithValidation.php" _blank
click transform "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/Transform.php" _blank
click process "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ProcessParameters.php" _blank
click variadic "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/IsVariadic.php" _blank
click mapInput "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/MapInput.php" _blank
click missing "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/MissingParameters.php" _blank
click extra "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/ExtraParameters.php" _blank
click validate "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/Validate.php" _blank
```

## The Output Collection Pipeline

The `OutputCollectionPipeline` is responsible for transforming the Bag collection data to the desired output array or JSON. The pipeline consists of the following steps:

```mermaid
graph TD;
toArray("Collection->toArray()") --> wrap
toJson("Collection->toJson()") --> wrap
jsonEncode("json_encode($collection)") --> wrap
unwrapped("Collection->unwrapped()") --> wrap
wrap(Wrap Collection*)
--> output(array or JSON string)

class toArray,toJson,jsonEncode,unwrapped mermaid-start
class output mermaid-end

click toArray "https://github.com/dshafik/bag/blob/main/src/Bag/Collection.php" _blank
click toJson "https://github.com/dshafik/bag/blob/main/src/Bag/Collection.php" _blank
click jsonEncode "https://github.com/dshafik/bag/blob/main/src/Bag/Collection.php" _blank
click unwrapped "https://github.com/dshafik/bag/blob/main/src/Bag/Collection.php" _blank
click wrap "https://github.com/dshafik/bag/blob/main/src/Bag/Pipelines/Pipes/WrapCollection.php" _blank
```

> [!NOTE]
> \* This step is only performed if the Bag is being converted to an array and/or JSON.


