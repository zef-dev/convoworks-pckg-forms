# Forms package for Convoworks


This package contains conversational workflow elements for managing form entries scenarios in the [Convoworks framework](https://github.com/zef-dev/convoworks-core). It contains elements that you can use in the conversation workflow, but the form data source is just described via the [IFormsContext](https://github.com/zef-dev/convoworks-pckg-forms/blob/main/src/Convo/Pckg/Forms/IFormsContext.php) interface.

When we are talking about workflow components (elements), we have to primarily consider voice and conversational design needs. Their properties, sub-flows and general behavior are tailored to make conversational workflow as easy as possible. They are not related to any particular forms plugin or a similar 3rd party service provider.

Form context is on the other hand a bridge between workflow elements (Convoworks) and the real, concrete form plugin you are using in your system.

Forms package favors field keys over numeric ids. When you are creating or updating entries, use field keys to define data. When you are loading entries, they will contain a `meta_values` field, an associative array containing field key / field value pairs.

## Forms context interface

`IFormsContext` describes methods that should be implemented by a target form system. If you have e.g. WordPress Formidable Forms plugin, you can easily enable it to be used with Convoworks by implementing this interface.

To be properly used in the Convoworks GUI, it also has to implement `IBasicServiceComponent` and `IServiceContext`. You might consider to start your implementation like this:

```php

class MyCustomFormsContext extends AbstractBasicComponent implements IFormsContext, IServiceContext

{


}

```

You can check for more about [developing custom packages](https://convoworks.com/docs/developers/develop-custom-packages/) on the Convoworks documentation and you can check our [Convoworks WP Plugin Package Template](https://github.com/zef-dev/convoworks-wp-plugin-package-template)


### `DummyFormContext`

Dummy implementation that can serve to test voice applications or as an example when creating your own `IFormsContext` implementation.

Here are few predefined features that it has:

* it will store entries in the Convoworks user scope
* required fields (for error handling)


## Workflow elements

All forms package workflow elements have the `context_id` property which hooks them to the context which implements the `IFormsContext` interface. That way elements are concentrated on the conversation workflow needs, while the real business logic is delegated to the concrete implementation.

Here are all common parameters:

* `context_id` - Id of the referenced form context (context component that implements `IFormsContext`)
* `result_var` - Variable that contains result. It is different for each element, so it will be described below.

Some elements have multiple sub-flows depending on the result we got. This kind of approach enables you to use less `IF` statements in your workflow. But in order not to force you to split workflow, some of the flows are optional and when left empty, the default flow will be executed.


### `CreateEntryElement`

This element will create an entry and will return newly created entry_id.

Parameters:

* `entry` - The entry data that will be written (a key value)
* `result_var` - Default `status`, name of the variable that contains additional information. If entry is created (`entry_id` : ID of the newly created entry), if failed ( `message` : string message, `errors` : array of detailed errors)

Flows:

* `ok` - executes when the entry could be created
* `validation_error` - executes when the entry has validation errors


### `UpdateEntryElement`

Element which updates existing entry data.

Parameters:

* `entry_id` - ID of the existing entry
* `entry` - The entry data that will overwrite the previous data (a key value)
* `result_var` - Default `status`, name of the variable that contains additional information. If entry is updated (`previous` : entry data before update, `updated` : entry after update), if failed ( `message` : string message, `errors` : array of detailed errors)

Flows:
* `ok` - will be executed when the entry is updated
* `validation_error` - executes when the entry has validation errors

### `DeleteEntryElement`

This element will delete an existing entry.

Parameters:

* `entry_id` - ID of the existing entry
* `result_var` - Default `status`, name of the variable that contains additional information (`previous` : previous form entry as you would get it with load entry element)

Flows:
* `ok` - will be executed when the entry is deleted


### `LoadEntryElement`

This element will load an existing entry by its id.

Parameters:

* `entry_id` - ID of the existing entry
* `result_var` - Default `status`, name of the variable that contains additional information (`entry`)

Flows:
* `ok` - will be executed when the entry is loaded

Single entry representation as JSON.

```json
{
        "entry_id" : "123",
        "user_id" : "123",
        "meta_values" : {
            "key_1" : "value 1",
            "key_2" : "value 2"
        }
    }
```


### `SearchEntryElement`

This element searches entries by the given search parameter.

Parameters:

* `search` - Search filter. Defined by a referenced form context.
* `offset` - Offset
* `limit` - Limit
* `order_by` - key/val pairs of field name (defined by form context implementation) and sort order (ASC|DESC)
* `result_var` - Default `status`, name of the variable that contains additional information (`result`: array of entry values)

Flows:
* `ok` - Executed when the form is loaded.
* `not found` - will be executed if no entry was found


For more information, please check out [convoworks.com](https://convoworks.com)
