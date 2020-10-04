<?php

declare(strict_types=1);

namespace Example\View;

use Example\Model\ExampleModel;
use Mini\Controller\Exception\BadInputException;

/**
 * Example view builder.
 */
class ExampleView
{
    /**
     * Get the example view to display its data.
     *
     * @param ExampleModel $model
     * @return string view template
     *
     * @throws BadInputException if no example data is returned
     */
    public function get(ExampleModel $model): string
    {
        if (!$model->id) {
            throw new BadInputException('Unknown example ID');
        }

        return view('app/example/detail', ['example' => $model]);
    }
}
