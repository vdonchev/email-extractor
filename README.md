# Email Extractor

Extract valid email addresses from a string or array of strings.
With a few helper methods like sortAsc/sortDesc, unique,
filterInclude/filterExclude, lowercase/uppercase included.

## Installation

`composer require donchev/email-extractor`

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
  1 => string 'allen@yaholo.com' (length=16)
```

## Method chaining design pattern

**Email Exporter** library uses method chaining. You should follow these simple rules:

- "extract()" OR "extractAll()" method SHOULD be called first.
- "export()" method SHOULD be called last.

_Otherwise EmailExtractorException will be thrown._

## Extractor helper methods

There are a few helper methods build-in. These can be called after **extract()/extractAll()** and before **export()**.

#### Methods

| Option Name       | Argument                                        | Description                                                                                                                                                                                            |
|-------------------|-------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| ->unique()        |                                                 | Remove all duplicates from the extracted emails. _[case sensitive]_                                                                                                                                    |
| ->lower()         |                                                 | Convert all extracted emails to lowercase.                                                                                                                                                             |
| ->upper()         |                                                 | Convert all extracted emails to uppercase.                                                                                                                                                             |
| ->filterExclude() | _(optional)_ array of strings to filter against | It excludes from the list all emails that are matched against the filter array. If array is not passed as argument it checks if there is filter array passed to the constructor. _[case sensitive]_    |
| ->filterInclude() | _(optional)_ array of strings to filter against | It excludes from the list all emails that are not matched against the filter array. If array is not passed as argument it checks if there is filter array passed to the constructor. _[case sensitive] |
| ->sortAsc()       |                                                 | Sort extracted emails list alphabetically (ASC)                                                                                                                                                        |
| ->sortDesc()      |                                                 | Sort extracted emails list alphabetically (DESC)                                                                                                                                                       |

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