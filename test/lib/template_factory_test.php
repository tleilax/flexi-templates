<?php

# Copyright (c)  2008 - Marcus Lunzenauer <mlunzena@uos.de>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

require_once dirname(__FILE__) . '/../flexi_tests.php';
Flexi_Tests::setup();

/**
 * Testcase for TemplateFactory.php.
 *
 * @package    flexi
 * @subpackage test
 *
 * @author    mlunzena
 * @copyright (c) Authors
 * @version   $Id: template_test.php 4194 2006-10-24 14:52:31Z mlunzena $
 */

class TemplateFactoryTestCase extends UnitTestCase {

  var $factory;

  function setUp() {
    $this->factory =& new Flexi_TemplateFactory(TEST_DIR . '/templates/factory_tests');
  }

  function tearDown() {
    unset($this->factory);
  }

  function test_should_create_factory() {
    $factory =& new Flexi_TemplateFactory('.');
    $this->assertNotNull($factory);
  }

  function test_should_create_factory_using_path() {
    $path = TEST_DIR . '/templates/factory_tests';
    $factory =& new Flexi_TemplateFactory($path);
    $this->assertNotNull($factory);
  }

  function test_should_open_template_using_relative_path() {
    $foo =& $this->factory->open('foo');
    $this->assertNotNull($foo);
  }

  function test_should_open_template_using_absolute_path() {
    $foo =& $this->factory->open(TEST_DIR . '/templates/factory_tests/foo');
    $this->assertNotNull($foo);
  }

  function test_should_raise_a_warning_trying_to_open_a_missing_template() {
    $this->expectError(new PatternExpectation('/Could not find template/'));
    $bar =& $this->factory->open('bar');
    $this->assertNull($bar);
  }

  function test_should_open_template_using_extension() {
    $foo =& $this->factory->open('foo.php');
    $this->assertNotNull($foo);
    $this->assertIsA($foo, 'Flexi_PhpTemplate');
  }

  function test_should_raise_an_error_when_opening_a_template_with_unknown_extension() {
    $this->expectError(new PatternExpectation('/Could not find class/'));
    $baz =& $this->factory->open('baz');
    $this->assertNull($baz);
  }

  function test_should_render_template() {
    $template =& $this->factory->open('foo');
    $out = $template->render(array('whom' => 'bar'));
    $this->assertEqual('Hallo, bar!', $out);
  }
}
