<?php

declare(strict_types=1);

namespace Example\View;

use Example\Model\AdditionModel;
use Mini\Controller\Exception\BadInputException;

/**
 * Example view builder.
 */
class AdditionView
{
    /**
     * Get the example view to display its data.
     *
     * @param AdditionModel $model
     * @return string view template
     * @throws BadInputException
     */
    public function get(AdditionModel $model): string
    {
        if (!$model->canSum()) {
            throw new BadInputException('Bad input found');
        }
        return view('app/example/addition-results', ['model' => $model]);
    }
}
