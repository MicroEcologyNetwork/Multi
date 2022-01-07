# Fields management


## Remove field

The built-in `map` and `editor` fields requires the front-end files via cdn, and if there are problems with the network, they can be removed in the following ways

Locate the file `app/Multi/bootstrap.php`. If the file does not exist, update `laravel-multi` and create this file.

```php

<?php

use MicroEcology\Multi\Form;

Form::forget('map');
Form::forget('editor');

// or

Form::forget(['map', 'editor']);

```

This removes the two fields, which can be used to remove the other fields.

## Extend the custom field

Extend a PHP code editor based on [codemirror](http://codemirror.net/index.html) with the following steps.

see [PHP mode](http://codemirror.net/mode/php/).

Download and unzip the [codemirror](http://codemirror.net/codemirror.zip) library to the front-end resource directory, for example, in the directory `public/packages/codemirror-5.20.2`.

Create a new field class `app/Multi/Extensions/PHPEditor.php`:

```php
<?php

namespace App\Multi\Extensions;

use MicroEcology\Multi\Form\Field;

class PHPEditor extends Field
{
    protected $view = 'multi.php-editor';

    protected static $css = [
        '/packages/codemirror-5.20.2/lib/codemirror.css',
    ];

    protected static $js = [
        '/packages/codemirror-5.20.2/lib/codemirror.js',
        '/packages/codemirror-5.20.2/addon/edit/matchbrackets.js',
        '/packages/codemirror-5.20.2/mode/htmlmixed/htmlmixed.js',
        '/packages/codemirror-5.20.2/mode/xml/xml.js',
        '/packages/codemirror-5.20.2/mode/javascript/javascript.js',
        '/packages/codemirror-5.20.2/mode/css/css.js',
        '/packages/codemirror-5.20.2/mode/clike/clike.js',
        '/packages/codemirror-5.20.2/mode/php/php.js',
    ];

    public function render()
    {
        $this->script = <<<EOT

CodeMirror.fromTextArea(document.getElementById("{$this->id}"), {
    lineNumbers: true,
    mode: "text/x-php",
    extraKeys: {
        "Tab": function(cm){
            cm.replaceSelection("    " , "end");
        }
     }
});

EOT;
        return parent::render();

    }
}

```

>Static resources in the class can also be imported from outside, see [Editor.php](https://github.com/z-song/laravel-multi/blob/1.3/src/Form/Field/Editor.php)

Create a view file `resources/views/multi/php-editor.blade.php`:

```php

<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('multi::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('multi::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
    </div>
</div>

```

Finally, find the file `app/Multi/bootstrap.php`, if the file does not exist, update `laravel-multi`, and then create this file, add the following code:

```
<?php

use App\Multi\Extensions\PHPEditor;
use MicroEcology\Multi\Form;

Form::extend('php', PHPEditor::class);

```

And then you can use PHP editor in [model-form](/en/model-form.md):

```

$form->php('code');

```

In this way, you can add any form fields you want to add.

## Integrate CKEditor

Here is another example to show you how to integrate ckeditor.

At first download [CKEditor](http://ckeditor.com/download), unzip to public directory, for example `public/packages/ckeditor/`.

Then Write Extension class `app/Multi/Extensions/Form/CKEditor.php`:
```php
<?php

namespace App\Multi\Extensions\Form;

use MicroEcology\Multi\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/packages/ckeditor/ckeditor.js',
        '/packages/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'multi.ckeditor';

    public function render()
    {
        $this->script = "$('textarea.{$this->getElementClass()}').ckeditor();";

        return parent::render();
    }
}
```
Add blade file `resources/views/multi/ckeditor.blade.php` for view `multi.ckeditor` : 
```php
<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('multi::form.error')

        <textarea class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        @include('multi::form.help-block')

    </div>
</div>

```
Register this extension in `app/Multi/bootstrap.php`:

```php
use MicroEcology\Multi\Form;
use App\Multi\Extensions\Form\CKEditor;

Form::extend('ckeditor', CKEditor::class);
```
After this you can use ckeditor in your form:

```php
$form->ckeditor('content');
```
