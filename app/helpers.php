<?php

declare(strict_types=1);

/**
 * This file is part of the guanguans/music-dl.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

use App\Exceptions\RuntimeException;
use Joli\JoliNotif\Util\OsHelper;

if (! function_exists('get_song_download_dir')) {
    /**
     * @throws \App\Exceptions\RuntimeException
     */
    function get_song_download_dir(string $dir = 'MusicDL'): string
    {
        $downloadDir = OsHelper::isWindows()
            ? sprintf('C:\\Users\\%s\\Downloads\\%s\\', get_current_user(), $dir)
            : sprintf('%s/Downloads/%s/', exec('cd ~; pwd'), $dir);
        if (! is_dir($downloadDir) && ! mkdir($downloadDir, 0777, true) && ! is_dir($downloadDir)) {
            throw new RuntimeException(sprintf('The directory "%s" was not created', $downloadDir));
        }

        return $downloadDir;
    }
}

if (! function_exists('get_song_save_path')) {
    /**
     * @throws \App\Exceptions\RuntimeException
     */
    function get_song_save_path(array $song): string
    {
        return get_song_download_dir().implode(',', $song['artist']).' - '.$song['name'].'.mp3';
    }
}
