# 自定义导出

laravel-multi的数据表格默认支持导出csv文件，

```php
<?php

namespace App\Multi\Extensions;

use MicroEcology\Multi\Grid\Exporters\AbstractExporter;

class CustomExporter extends AbstractExporter
{
    public function export()
    {
        $filename = $this->getTable().'.csv';

        $data = $this->getData();

        $output = '';

        $headers = [
            'Content-Encoding'    => 'UTF-8',
            'Content-Type'        => 'text/csv;charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        response(rtrim($output, "\n"), 200, $headers)->send();

        exit;
    }
}
```