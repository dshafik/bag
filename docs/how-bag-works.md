# How Bag Works

Bag works by utilizing a pipelines to process the input and output data. There is an `InputPipeline` and an `OutputPipeline`. 

## The Input Pipeline

The `InputPipeline` is responsible for processing the input data so that the `Bag` object can be created. The pipeline
consists of the following steps:

```mermaid
graph TD;
start("Bag::from($data)")
--> transform(Transform Input)
--> process(Process Parameters) 
--> map(Create Name Map)
--> variadic(Is Variadic?)
--> mapInput(Map Input)
--> fill(Fill Laravel Request Params)
-- Finalized Input Values --> missing{Missing Parameters?}
missing -- Yes --> errorMissingProperties(MissingPropertiesException)
missing -- No --> extra{Extra Parameters?}
extra -- Yes --> errorExtraProperties(ExtraPropertiesException)
extra -- No --> validate(Validate)
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
class missing,extra,valid,initialized mermaid-decision
class bag mermaid-end

click transform "https://dshafik.github.io/bag"
```

## The Output Pipeline

The `OutputPipeline` is responsible transforming the Bag data to the desired output array or JSON. The pipeline consists of the following steps:

```mermaid
graph TD;
toArray("Bag->toArray()") --> processParameters
toJson("Bag->toJson()") --> processParameters
get("Bag->get()") --> processParameters
processParameters(Process Parameters)
--> processProperties(Process Properties)
--> getValues(Get Values)
--> hide("Remove Hidden Attributes*")
--> hideJson("Remove Hidden JSON Attributes*")
--> cast(Cast Output)
--> mapOutput(Map Output)
--> wrap(Wrap Output*)
--> output(array or JSON string)

class toArray,toJson,get mermaid-start
class output mermaid-end
class hide,hideJson,wrap mermaid-conditional
```

> [!NOTE]
> \* These steps are only performed if the Bag is being converted to an array or JSON.
