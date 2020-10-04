<?php

declare(strict_types=1);

namespace Example\Controller;

use Example\Model\ExampleModel;
use Example\View\ExampleView;
use Mini\Controller\Controller;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;

/**
 * Example entrypoint logic.
 */
class ExampleController extends Controller
{
    /**
     * Example view model.
     *
     * @var ExampleModel|null
     */
    protected $model = null;

    /**
     * Example view builder.
     *
     * @var ExampleView|null
     */
    protected $view = null;

    /**
     * Setup.
     */
    public function __construct()
    {
        $this->model = container(ExampleModel::class);;
        $this->view = container(ExampleView::class);
    }

    /**
     * Create an example and display its data.
     *
     * @param Request $request http request
     *
     * @return string view template
     * @throws BadInputException
     * @throws \Exception
     */
    public function createExample(Request $request): string
    {
        if (!$code = $request->request->get('code')) {
            throw new BadInputException('Example code missing');
        }

        if (!$description = $request->request->get('description')) {
            throw new BadInputException('Example description missing');
        }
        $attributes = $request->request->all();
        $attributes['created'] = now();
        $this->model
            ->fill($attributes)
            ->insert();
        return $this->view->get($this->model);
    }
}
