<?php

declare(strict_types = 1);

namespace Example\Tests\Unit\Controller;

use Example\Model\ExampleModel;
use Example\Tests\BaseCase;
use Example\View\ExampleView;
use Mini\Controller\Exception\BadInputException;

/**
 * Example view builder test.
 */
class ExampleViewTest extends BaseCase
{
    /**
     * Reset the service containers
     */
    public function setUp(): void
    {
        parent::setUp();
        container()->setService(ExampleModel::class, new ExampleModel());
        container()->setService(ExampleView::class, new ExampleView());
    }

    /**
     * Test getting an example view to display its data.
     * 
     * @return void
     */
    public function testGet(): void
    {
        $model = (new ExampleModel())->fill([
            'id' => 1,
            'created' => '2020-10-03 20:10:09',
            'code' => 'TESTCODE',
            'description' => 'Test description',
        ]);

        $view = $this->getClass(ExampleView::class)->get($model);

        $this->assertNotEmpty($view);
        $this->assertIsString($view);

        // Look for the newly created example
        $this->assertStringContainsString('TESTCODE', $view);
        $this->assertStringContainsString('Test description', $view);
    }

    /**
     * Test getting an example view errors on unknown example ID.
     * 
     * @return void
     */
    public function testGetErrorsOnUnknownExampleId(): void
    {
        $this->expectException(BadInputException::class);

        $model = new ExampleModel();

        $this->getClass(ExampleView::class)->get($model);
    }
}
