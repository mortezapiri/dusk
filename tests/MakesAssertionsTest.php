<?php

namespace Laravel\Dusk\Tests;

use Facebook\WebDriver\Remote\RemoteWebElement;
use Laravel\Dusk\Browser;
use Mockery as m;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use stdClass;

class MakesAssertionsTest extends TestCase
{
    public function test_assert_title()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('getTitle')->andReturn(
            'foo'
        );
        $browser = new Browser($driver);

        $browser->assertTitle('foo');

        try {
            $browser->assertTitle('Foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Expected title [Foo] does not equal actual title [foo].',
                $e->getMessage()
            );
        }
    }

    public function test_assert_title_contains()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('getTitle')->andReturn(
            'foo'
        );
        $browser = new Browser($driver);

        $browser->assertTitleContains('fo');

        try {
            $browser->assertTitleContains('Fo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Did not see expected value [Fo] within title [foo].',
                $e->getMessage()
            );
        }
    }

    public function test_assert_attribute()
    {
        $driver = m::mock(stdClass::class);
        $element = m::mock(stdClass::class);
        $element->shouldReceive('getAttribute')->with('bar')->andReturn(
            'joe',
            null,
            'sue'
        );
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('foo')->andReturn('Foo');
        $resolver->shouldReceive('findOrFail')->with('foo')->andReturn($element);
        $browser = new Browser($driver, $resolver);

        $browser->assertAttribute('foo', 'bar', 'joe');

        try {
            $browser->assertAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Did not see expected attribute [bar] within element [Foo].',
                $e->getMessage()
            );
        }

        try {
            $browser->assertAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected 'bar' attribute [joe] does not equal actual value [sue].",
                $e->getMessage()
            );
        }
    }

    public function test_assert_data_attribute()
    {
        $driver = m::mock(stdClass::class);
        $element = m::mock(stdClass::class);
        $element->shouldReceive('getAttribute')->with('data-bar')->andReturn(
            'joe',
            null,
            'sue'
        );
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('foo')->andReturn('Foo');
        $resolver->shouldReceive('findOrFail')->with('foo')->andReturn($element);
        $browser = new Browser($driver, $resolver);

        $browser->assertDataAttribute('foo', 'bar', 'joe');

        try {
            $browser->assertDataAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Did not see expected attribute [data-bar] within element [Foo].',
                $e->getMessage()
            );
        }

        try {
            $browser->assertDataAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected 'data-bar' attribute [joe] does not equal actual value [sue].",
                $e->getMessage()
            );
        }
    }

    public function test_assert_aria_attribute()
    {
        $driver = m::mock(stdClass::class);
        $element = m::mock(stdClass::class);
        $element->shouldReceive('getAttribute')->with('aria-bar')->andReturn(
            'joe',
            null,
            'sue'
        );
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('foo')->andReturn('Foo');
        $resolver->shouldReceive('findOrFail')->with('foo')->andReturn($element);
        $browser = new Browser($driver, $resolver);

        $browser->assertAriaAttribute('foo', 'bar', 'joe');

        try {
            $browser->assertAriaAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Did not see expected attribute [aria-bar] within element [Foo].',
                $e->getMessage()
            );
        }

        try {
            $browser->assertAriaAttribute('foo', 'bar', 'joe');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected 'aria-bar' attribute [joe] does not equal actual value [sue].",
                $e->getMessage()
            );
        }
    }

    public function test_assert_present()
    {
        $driver = m::mock(stdClass::class);
        $element = m::mock(stdClass::class);
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('foo')->andReturn('body foo');
        $resolver->shouldReceive('find')->with('foo')->andReturn(
            $element,
            null
        );
        $browser = new Browser($driver, $resolver);

        $browser->assertPresent('foo');

        try {
            $browser->assertPresent('foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Element [body foo] is not present.',
                $e->getMessage()
            );
        }
    }

    public function test_assert_enabled()
    {
        $driver = m::mock(stdClass::class);
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForField->isEnabled')->andReturn(
            true,
            false
        );
        $browser = new Browser($driver, $resolver);

        $browser->assertEnabled('foo');

        try {
            $browser->assertEnabled('foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected element [foo] to be enabled, but it wasn't.",
                $e->getMessage()
            );
        }
    }

    public function test_assert_disabled()
    {
        $driver = m::mock(stdClass::class);
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForField->isEnabled')->andReturn(
            false,
            true
        );
        $browser = new Browser($driver, $resolver);

        $browser->assertDisabled('foo');

        try {
            $browser->assertDisabled('foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected element [foo] to be disabled, but it wasn't.",
                $e->getMessage()
            );
        }
    }

    public function test_assert_button_enabled()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage("Expected button [Cant press me] to be enabled, but it wasn't.");

        $driver = m::mock(stdClass::class);
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForButtonPress->isEnabled')->andReturn(
            true,
            false
        );
        $browser = new Browser($driver, $resolver);

        $browser->assertButtonEnabled('Press me');

        $browser->assertButtonEnabled('Cant press me');
    }

    public function test_assert_button_disabled()
    {
        $this->expectException(ExpectationFailedException::class);
        $this->expectExceptionMessage("Expected button [Press me] to be disabled, but it wasn't.");

        $driver = m::mock(stdClass::class);
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForButtonPress->isEnabled')->twice()->andReturn(
            false,
            true
        );
        $browser = new Browser($driver, $resolver);

        $browser->assertButtonDisabled('Cant press me');

        $browser->assertButtonDisabled('Press me');
    }

    public function test_assert_focused()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('switchTo->activeElement->equals')->with('element')->andReturn(
            true,
            false
        );
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForField')->with('foo')->andReturn('element');
        $browser = new Browser($driver, $resolver);

        $browser->assertFocused('foo');

        try {
            $browser->assertFocused('foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Expected element [foo] to be focused, but it wasn't.",
                $e->getMessage()
            );
        }
    }

    public function test_assert_not_focused()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('switchTo->activeElement->equals')->with('element')->andReturn(
            false,
            true
        );
        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveForField')->with('foo')->andReturn('element');
        $browser = new Browser($driver, $resolver);

        $browser->assertNotFocused('foo');

        try {
            $browser->assertNotFocused('foo');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Expected element [foo] not to be focused, but it was.',
                $e->getMessage()
            );
        }
    }

    public function test_assert_selected()
    {
        $driver = m::mock(stdClass::class);

        $element = m::mock(RemoteWebElement::class);
        $element->shouldReceive('isSelected')->andReturn(true);

        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('resolveSelectOptions')
            ->with('select[name="users"]', [2])
            ->andReturn([$element]);

        $browser = new Browser($driver, $resolver);

        $browser->assertSelected('select[name="users"]', 2);

        try {
            $browser->assertNotSelected('select[name="users"]', 2);
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'Unexpected value [2] selected for [select[name="users"]].',
                $e->getMessage()
            );
        }
    }

    public function test_assert_vue_contains()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('executeScript')->andReturn(['john']);

        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('@vue-component')->andReturn('body foo');

        $browser = new Browser($driver, $resolver);

        $browser->assertVueContains('users', 'john', '@vue-component');

        try {
            $browser->assertVueDoesNotContain('users', 'john', '@vue-component');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                "Failed asserting that an array does not contain 'john'.",
                $e->getMessage()
            );
        }
    }

    public function test_assert_vue_contains_with_no_result()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('executeScript')->andReturn(null);

        $resolver = m::mock(stdClass::class);
        $resolver->shouldReceive('format')->with('@vue-component')->andReturn('body foo');

        $browser = new Browser($driver, $resolver);

        try {
            $browser->assertVueContains('users', 'john', '@vue-component');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'The attribute for key [users] is not an array.',
                $e->getMessage()
            );
        }
    }

    public function test_assert_script()
    {
        $driver = m::mock(stdClass::class);
        $driver->shouldReceive('executeScript')->withArgs(['return 1==1'])->andReturn(true);
        $driver->shouldReceive('executeScript')->withArgs(['return 1==1'])->andReturn(true);
        $driver->shouldReceive('executeScript')->withArgs(['return 1==2'])->andReturn(false);
        $driver->shouldReceive('executeScript')->withArgs(["return 'some string'"])->andReturn('some string');

        $resolver = m::mock(stdClass::class);

        $browser = new Browser($driver, $resolver);

        $browser->assertScript('return 1==1');
        $browser->assertScript('1==1');
        $browser->assertScript("'some string'", 'some string');

        try {
            $browser->assertScript('1==2');
            $this->fail();
        } catch (ExpectationFailedException $e) {
            $this->assertStringContainsString(
                'JavaScript expression [return 1==2] mismatched.',
                $e->getMessage()
            );
            $this->assertStringContainsString(
                'Failed asserting that false matches expected true.',
                $e->getMessage()
            );
        }
    }
}
