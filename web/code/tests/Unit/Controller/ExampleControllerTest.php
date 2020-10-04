<?php

declare(strict_types=1);

namespace Example\Tests\Unit\Controller;

use Example\Controller\ExampleController;
use Example\Model\ExampleModel;
use Example\Tests\BaseCase;
use Example\View\ExampleView;
use Mini\Controller\Exception\BadInputException;
use Mini\Http\Request;
use Mini\Util\DateTime;

/**
 * Example entrypoint logic test.
 */
class ExampleControllerTest extends BaseCase
{
    /**
     * Test creating an example and displaying its data.
     *
     * @return void
     */
    public function testCreateExampleWithGoodInput(): void
    {
        // Override the created column input set by `now()`
        DateTime::setTestNow(DateTime::create(2020, 7, 14, 12, 00, 00));

        $model = $this->getMock(ExampleModel::class);
        $model->shouldReceive('fill')
            ->once()
            ->with([
                'code' => 'TESTCODE',
                'description' => 'Test description',
                'created' => '2020-07-14 12:00:00',
            ])
            ->andReturn(\Mockery::self())
        ;
        $model->shouldReceive('insert')
            ->once()
            ->andReturn(1);
        $this->setMock(ExampleModel::class, $model);

        $view = $this->getMock(ExampleView::class);
        $view->shouldReceive('get')
            ->once()
            ->with($model)
        ;
        $this->setMock(ExampleView::class, $view);

        $request = new Request([], [
            'code' => 'TESTCODE',
            'description' => 'Test description'
        ]);

        $this->getClass(ExampleController::class)->createExample($request);
    }

    /**
     * Test creating an example errors on a missing example code.
     *
     * @return void
     */
    public function testCreateExampleErrorsOnMissingCode(): void
    {
        $this->expectException(BadInputException::class);

        $request = new Request([], ['description' => 'Test description']);

        $this->getClass('Example\Controller\ExampleController')->createExample($request);
    }

    /**
     * Test creating an example errors on a missing example description.
     *
     * @return void
     */
    public function testCreateExampleErrorsOnMissingDescription(): void
    {
        $this->expectException(BadInputException::class);

        $request = new Request([], ['code' => 'TESTCODE']);

        $this->getClass('Example\Controller\ExampleController')->createExample($request);
    }
}
