# Email Extractor

Extract in `array` ALL valid email addresses from a `string` or `string array`.
This library also includes a few helper methods to modify the output array.

## Installation

`composer require donchev/email-extractor`

## Object Instantiation
`EmailExtractor` object accept 2 optional parameters in it's `__constructor()` method:
* `array|null $filter` An array of words used for filtering matched emails. If not passed to the constructor, an empty `array` is used when/if `filterAsc()` or `filterDesc()` is called.
* `string|null $regex` A valid regex that match an email address. If not passed to the constructor, the default build-in regex will be used. **(NOTE: It is recommended to use the build-in regex, except if you know what you are doing.)**

## Simple Usage

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

$extractor = new EmailExtractor();

$content = file_get_contents("email.eml");

$emails = $extractor
    ->extract($content)
    ->export();
```

## Advanced usage

```php 
<?php

require_once __DIR__ . '/vendor/autoload.php';

$filter = ['@gmail.com', 'allen']
$content = ["allen@yahoo.com, sample@gmail.com, test@mail.com", "mail@mail.me"];

$extractor = new EmailExtractor($filter);

$emails = $extractor
    ->extractAll($content)
    ->filterInclude()
    ->lower()
    ->unique()
    ->sortDesc()
    ->export();
```

##### Output:

```
array (size=2)
  0 => string 'sample@gmail.com' (length=16)
  1 => string 'allen@yahoo.com' (length=16)
```

## Method chaining design pattern

**Email Exporter** library uses method chaining. You should follow one simple rule:

- `extract()` OR `extractAll()` method **SHOULD** be called first.

_Otherwise, `EmailExtractorException` will be thrown._

## Helper methods

There are a few helper methods build-in. These can be called after `extract()`/`extractAll()`.

#### Methods

| Option Name       | Argument                                        | Description                                                                                                                                                                                             |
|-------------------|-------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `unique() `       |                                                 | Remove all duplicates from the extracted emails. _[case sensitive]_                                                                                                                                     |
| `lower()`         |                                                 | Convert all extracted emails to lowercase.                                                                                                                                                              |
| `upper()`         |                                                 | Convert all extracted emails to uppercase.                                                                                                                                                              |
| `filterExclude()` | _(optional)_ array of strings to filter against | It excludes from the list all emails that are matched against the filter array. If array is not passed as argument it checks if there is filter array passed to the constructor. _[case sensitive]_     |
| `filterInclude()` | _(optional)_ array of strings to filter against | It excludes from the list all emails that are not matched against the filter array. If array is not passed as argument it checks if there is filter array passed to the constructor. _[case sensitive]_ |
| `sortAsc()`       |                                                 | Sort extracted emails list alphabetically. _[ascending]_                                                                                                                                                |
| `sortDesc()`      |                                                 | Sort extracted emails list alphabetically. _[descending]_                                                                                                                                               |

## Author

[Donchev](https://github.com/vdonchev)

## License

The MIT License (MIT)

Copyright (c) 2022 Donchev

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.