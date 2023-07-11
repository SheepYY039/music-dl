<?php

/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

/**
 * This file is part of the guanguans/music-dl.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace App\Commands;

use App\Concerns\Sanitizer;
use App\Contracts\Music;
use App\MusicManager;
use App\Support\Utils;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use SebastianBergmann\Timer\ResourceUsageFormatter;
use SebastianBergmann\Timer\Timer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class MusicCommand extends Command
{
    use Sanitizer;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'music
                            {keyword? : Search keyword for music}
                            {--driver=sequence : Specify the search driver(sequence、async)}
                            {--d|dir= : Specify the download directory}
                            {--sources=* : Specify the music sources(tencent、netease、kugou)}
                            ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Search and download music';

    private array $config;
    private Music $music;

    /**
     * Execute the console command.
     */
    public function handle(Timer $timer, ResourceUsageFormatter $resourceUsageFormatter): int
    {
        return collect()
            ->pipe(function () use ($timer, &$songs, &$sanitizedSongs, $resourceUsageFormatter, &$choices, &$lastKey): Collection {
                $this->line($this->config['logo']);
                windows_os() and $this->line($this->config['windows_tips']);
                $keyword = str($this->argument('keyword') ?? $this->ask($this->config['search_tips'], '腰乐队'))->trim()->toString();
                $sources = array_filter((array) $this->option('sources')) ?: $this->config['sources'];

                $this->line(sprintf($this->config['searching'], $keyword));
                $timer->start();
                $songs = $this->music->search($keyword, $sources);
                $duration = $timer->stop();
                if (empty($songs)) {
                    $this->line($this->config['empty_result']);
                    $this->reCall();
                }

                $this->newLine();
                $sanitizedSongs = $this->sanitizes($songs, $keyword);
                $this->table($this->config['table_header'], $sanitizedSongs);
                $this->info($resourceUsageFormatter->resourceUsage($duration));
                if (! $this->confirm($this->config['confirm_download'], true)) {
                    $this->reCall();
                }

                $choices = collect($sanitizedSongs)
                    ->transform(static fn (array $song): string => implode('  ', Arr::except($song, [0])))
                    ->add($this->config['download_all_songs']);

                return collect($this->choice(
                    $this->config['download_choice_tips'],
                    $choices->all(),
                    $lastKey = ($choices->count() - 1),
                    null,
                    true
                ));
            })
            ->transform(static fn (string $selectedValue) => $choices->search($selectedValue))
            ->pipe(static function (Collection $selectedKeys) use ($lastKey, $songs): Collection {
                if (\in_array($lastKey, $selectedKeys->all(), true)) {
                    return collect($songs);
                }

                return collect($songs)->only($selectedKeys);
            })
            ->each(function (array $song, int $index) use ($sanitizedSongs): void {
                try {
                    $this->table($sanitizedSongs[$index], []);
                    $savePath = Utils::get_save_path($song, $this->option('dir'));
                    $this->music->download($song['url'], $savePath);
                    $this->line(sprintf($this->config['save_path_tips'], $savePath));
                } catch (\Throwable $throwable) {
                    $this->line(sprintf($this->config['download_failed_tips'], $throwable->getMessage()));
                } finally {
                    $this->newLine();
                }
            })
            ->when(
                ! windows_os(),
                fn () => $this->notify(
                    config('app.name'),
                    $this->option('dir') ?: Utils::get_default_save_dir(),
                    $this->config['success_icon']
                )
            )
            ->pipe(fn (): int => $this->reCall());
    }

    /**
     * Define the command's schedule.
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }

    /**
     * @throws BindingResolutionException
     *
     * @noinspection PhpMissingParentCallCommonInspection
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        File::ensureDirectoryExists($this->option('dir') ?: Utils::get_default_save_dir());
        $this->config = config('music-dl');
        $this->music = $this->laravel->make(MusicManager::class)->driver($this->option('driver'));
    }

    protected function reCall(): int
    {
        return $this->call(
            self::class,
            $this->arguments() + collect($this->options())
                ->mapWithKeys(static fn ($option, string $key): array => ["--$key" => $option])
                ->all()
        );
    }
}
