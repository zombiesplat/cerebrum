<?php

declare(strict_types=1);

namespace Example\Controller;

use Example\Model\AdditionModel;
use Example\View\AdditionView;
use Mini\Controller\Controller;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;

/**
 * Example entrypoint logic.
 */
class AdditionController extends Controller
{
    /**
     * Example view model.
     *
     * @var AdditionModel|null
     */
    protected $model = null;

    /**
     * Example view builder.
     *
     * @var AdditionView|null
     */
    protected $view = null;

    /**
     * Setup.
     */
    public function __construct()
    {
        $this->model = container(AdditionModel::class);;
        $this->view = container(AdditionView::class);
    }

    /**
     * Show the addition form page.
     *
     * @param Request $request http request
     *
     * @return string view template
     */
    public function index(Request $request): string
    {
        return view('app/example/addition-form', ['version' => getenv('APP_VERSION')]);
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
    public function getSum(Request $request): string
    {
        if (!$addend_left = $request->request->get('addend_left')) {
            throw new BadInputException('First number is required');
        }
        if (!$addend_right = $request->request->get('addend_left')) {
            throw new BadInputException('Second number is required');
        }

        if (!is_numeric($addend_left)) {
            throw new BadInputException("First number is not numeric");
        }
        if (!is_numeric($addend_right)) {
            throw new BadInputException("Second number is not numeric");
        }

        $attributes = $request->request->all();
        $this->model->fill($attributes);
        return $this->view->get($this->model);
    }
}
