<?php

namespace ItDevgroup\LaravelEntityFileTable\Console\Commands;

use ItDevgroup\LaravelEntityFileTable\Providers\EntityFileTableServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

/**
 * Class EntityFileTablePublishCommand
 * @package ItDevgroup\LaravelEntityFileTable\Console\Commands
 */
class EntityFileTablePublishCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'entity:file:table:publish {--tag=* : Tag for published}';
    /**
     * @var string
     */
    protected $description = 'Publish files for entity file table';
    /**
     * @var array
     */
    private array $files = [];
    /**
     * @var array
     */
    private array $fileTags = [
        'config',
        'migration',
    ];

    /**
     * @return void
     */
    public function handle()
    {
        $option = is_array($this->option('tag')) && !empty($this->option('tag')) ? $this->option('tag')[0] : '';

        $this->parsePublishedFiles();

        switch ($option) {
            case 'config':
                $this->copyConfig();
                break;
            case 'migration':
                $this->copyMigration();
                break;
            default:
                $this->error('Not selected tag');
                break;
        }
    }

    /**
     * @return void
     */
    private function parsePublishedFiles(): void
    {
        $index = 0;
        foreach (EntityFileTableServiceProvider::pathsToPublish(EntityFileTableServiceProvider::class) as $k => $v) {
            $this->files[$this->fileTags[$index]] = [
                'from' => $k,
                'to' => $v,
            ];
            $index++;
        }
    }

    /**
     * @return void
     */
    private function copyConfig(): void
    {
        $this->copyFiles($this->files['config']['from'], $this->files['config']['to']);
    }

    /**
     * @return void
     */
    private function copyMigration(): void
    {
        $filename = sprintf(
            '%s_create_%s.php',
            now()->format('Y_m_d_His'),
            Config::get('entity_file_table.table')
        );

        $this->copyFile(
            $this->files['migration']['from'] . DIRECTORY_SEPARATOR . 'create_files.stub',
            $this->files['migration']['to'] . DIRECTORY_SEPARATOR . $filename,
            Config::get('entity_file_table.table')
        );
    }

    /**
     * @param string $from
     * @param string $to
     */
    private function copyFiles(string $from, string $to): void
    {
        if (!file_exists($to)) {
            mkdir($to, 0755, true);
        }
        $from = rtrim($from, '/') . '/';
        $to = rtrim($to, '/') . '/';
        foreach (scandir($from) as $file) {
            if (!is_file($from . $file)) {
                continue;
            }

            $path = strtr(
                $to . $file,
                [
                    base_path() => ''
                ]
            );

            if (file_exists($to . $file)) {
                $this->info(
                    sprintf(
                        'File "%s" skipped',
                        $path
                    )
                );
                continue;
            }

            copy(
                $from . $file,
                $to . $file
            );

            $content = file_get_contents($to . $file);
            $content = strtr($content, ['{{TABLE_NAME}}' => Config::get('entity_file_table.table')]);
            file_put_contents($to . $file, $content);

            $this->info(
                sprintf(
                    'File "%s" copied',
                    $path
                )
            );
        }
    }

    /**
     * @param string $from
     * @param string $to
     * @param string|null $table
     */
    private function copyFile(string $from, string $to, ?string $table = null): void
    {
        copy(
            $from,
            $to
        );

        $content = file_get_contents($to);
        $content = strtr($content, [
            '{{TABLE_NAME}}' => $table,
        ]);
        file_put_contents($to, $content);

        $path = strtr(
            $to,
            [
                base_path() => ''
            ]
        );

        $this->info(
            sprintf(
                'File "%s" copied',
                $path
            )
        );
    }
}
