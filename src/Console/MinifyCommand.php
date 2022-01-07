<?php

namespace Micro\Multi\Console;

use Micro\Multi\Multi;
use Micro\Multi\Facades\Multi as MultiFacade;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use MatthiasMullie\Minify;

class MinifyCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'multi:minify {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Minify the CSS and JS';

    /**
     * @var array
     */
    protected $assets = [
        'css' => [],
        'js'  => [],
    ];

    /**
     * @var array
     */
    protected $excepts = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!class_exists(Minify\Minify::class)) {
            $this->error('To use `multi:minify` command, please install [matthiasmullie/minify] first.');

            return;
        }

        if ($this->option('clear')) {
            return $this->clearMinifiedFiles();
        }

        MultiFacade::bootstrap();

        $this->loadExcepts();

        $this->minifyCSS();
        $this->minifyJS();

        $this->generateManifest();

        $this->comment('JS and CSS are successfully minified:');
        $this->line('  '.Multi::$min['js']);
        $this->line('  '.Multi::$min['css']);

        $this->line('');

        $this->comment('Manifest successfully generated:');
        $this->line('  '.Multi::$manifest);
    }

    protected function loadExcepts()
    {
        $excepts = config('multi.minify_assets.excepts', []);

        $this->excepts = array_merge($excepts, Multi::$minifyIgnores);
    }

    protected function clearMinifiedFiles()
    {
        @unlink(public_path(Multi::$manifest));
        @unlink(public_path(Multi::$min['js']));
        @unlink(public_path(Multi::$min['css']));

        $this->comment('Following files are cleared:');

        $this->line('  '.Multi::$min['js']);
        $this->line('  '.Multi::$min['css']);
        $this->line('  '.Multi::$manifest);
    }

    protected function minifyCSS()
    {
        $css = collect(array_merge(Multi::$css, Multi::baseCss()))
            ->unique()->map(function ($css) {
                if (url()->isValidUrl($css)) {
                    $this->assets['css'][] = $css;

                    return;
                }

                if (in_array($css, $this->excepts)) {
                    $this->assets['css'][] = $css;

                    return;
                }

                if (Str::contains($css, '?')) {
                    $css = substr($css, 0, strpos($css, '?'));
                }

                return public_path($css);
            })->filter();

        $minifier = new Minify\CSS();

        $minifier->add(...$css);

        $minifier->minify(public_path(Multi::$min['css']));
    }

    protected function minifyJS()
    {
        $js = collect(array_merge(Multi::$js, Multi::baseJs()))
            ->unique()->map(function ($js) {
                if (url()->isValidUrl($js)) {
                    $this->assets['js'][] = $js;

                    return;
                }

                if (in_array($js, $this->excepts)) {
                    $this->assets['js'][] = $js;

                    return;
                }

                if (Str::contains($js, '?')) {
                    $js = substr($js, 0, strpos($js, '?'));
                }

                return public_path($js);
            })->filter();

        $minifier = new Minify\JS();

        $minifier->add(...$js);

        $minifier->minify(public_path(Multi::$min['js']));
    }

    protected function generateManifest()
    {
        $min = collect(Multi::$min)->mapWithKeys(function ($path, $type) {
            return [$type => sprintf('%s?id=%s', $path, md5(uniqid()))];
        });

        array_unshift($this->assets['css'], $min['css']);
        array_unshift($this->assets['js'], $min['js']);

        $json = json_encode($this->assets, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        file_put_contents(public_path(Multi::$manifest), $json);
    }
}
