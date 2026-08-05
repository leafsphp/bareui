<?php

/*
|--------------------------------------------------------------------------
| Test harness for leafs/bareui
|--------------------------------------------------------------------------
| Each test gets a sandbox views directory. BareUI keeps its config in a
| static property, so it is reset via reflection before every test.
| BareUI reports failures through trigger_error, so a collecting error
| handler is provided to assert on raised notices.
*/

define('SANDBOX', '/tmp/bareui-test-sandbox' . (getenv('TEST_TOKEN') ? '-' . getenv('TEST_TOKEN') : ''));

function setupBareUIEnv(): void
{
    if (is_dir(SANDBOX)) {
        exec('rm -rf ' . escapeshellarg(SANDBOX));
    }

    mkdir(SANDBOX . '/views', 0777, true);

    $reflection = new ReflectionClass(\Leaf\BareUI::class);
    $reflection->setStaticPropertyValue('config', [
        'path' => SANDBOX . '/views',
        'params' => [],
    ]);
}

/** Write a bare UI template into the sandbox views dir */
function template(string $name, string $content): void
{
    file_put_contents(SANDBOX . "/views/$name.view.php", $content);
}

/** Run $callback while collecting php notices/warnings, returns [result, errors] */
function captureErrors(callable $callback): array
{
    $errors = [];

    set_error_handler(function ($severity, $message) use (&$errors) {
        $errors[] = $message;

        return true;
    });

    try {
        $result = $callback();
    } finally {
        restore_error_handler();
    }

    return [$result, $errors];
}

uses()->beforeEach(fn () => setupBareUIEnv())->in(__DIR__);
