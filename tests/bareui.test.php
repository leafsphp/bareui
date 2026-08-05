<?php

use Leaf\BareUI;

test('renders a template with data', function () {
    template('hello', 'Hello, <?php echo $name; ?>!');

    expect(BareUI::render('hello', ['name' => 'Leaf']))->toBe('Hello, Leaf!');
});

test('output is raw php with no escaping layer', function () {
    template('raw', '<?php echo $html; ?>');

    expect(BareUI::render('raw', ['html' => '<b>bold</b>']))->toBe('<b>bold</b>');
});

test('the .view.php suffix is optional', function () {
    template('suffix', 'works');

    expect(BareUI::render('suffix'))->toBe('works');
    expect(BareUI::render('suffix.view.php'))->toBe('works');
});

test('any php construct works inside templates', function () {
    template('logic', '<?php foreach ($items as $item): ?><li><?php echo strtoupper($item); ?></li><?php endforeach; ?>');

    expect(BareUI::render('logic', ['items' => ['a', 'b']]))->toBe('<li>A</li><li>B</li>');
});

test('config params are available in every template', function () {
    template('global', '<?php echo $appName; ?>');

    BareUI::config('params', ['appName' => 'Leaf App']);

    expect(BareUI::render('global'))->toBe('Leaf App');
});

test('config accepts an array of settings', function () {
    mkdir(SANDBOX . '/other');
    file_put_contents(SANDBOX . '/other/page.view.php', 'from other dir');

    BareUI::config(['path' => SANDBOX . '/other']);

    expect(BareUI::render('page'))->toBe('from other dir');
});

test('templates can render partials through the $template variable', function () {
    template('partial', 'partial-content');
    template('page', 'before|<?php echo $template::render("partial"); ?>|after');

    expect(BareUI::render('page'))->toBe('before|partial-content|after');
});

test('a missing view raises an error and renders nothing', function () {
    [$result, $errors] = captureErrors(fn () => BareUI::render('ghost'));

    expect($errors[0])->toContain('ghost.view.php could not be found');
    expect((string) $result)->toBe('');
});

test('a throwable inside a template is reported without leaking partial output', function () {
    template('broken', 'partial output<?php throw new Exception("boom"); ?>');

    [$result, $errors] = captureErrors(fn () => BareUI::render('broken'));

    expect(implode(' ', $errors))->toContain('boom');
    expect((string) $result)->toContain('partial output');
});
