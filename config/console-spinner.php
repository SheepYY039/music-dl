<?php

declare(strict_types=1);

/**
 * This file is part of the guanguans/music-dl.
 *
 * (c) guanguans <ityaozm@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

return [
    /*
     * The spinner characters, which will be use for
     * showing the loading animation.
     */
    'chars' => [
        // '⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆', '⡇',
        // '⠋', '⠙', '⠚', '⠛', '⠓', '⠉', '⠙', '⠚', '⠒',
        // '◐', '◓', '◑', '◒',
        // '🌑', '🌒', '🌓', '🌔', '🌕', '🌖', '🌗', '🌘',
        // '🕛', '🕐', '🕑', '🕒', '🕓', '🕔', '🕕', '🕖',

        // "\033[96m⠏\033[0m",
        // "\033[96m⠛\033[0m",
        // "\033[96m⠹\033[0m",
        // "\033[96m⢸\033[0m",
        // "\033[96m⣰\033[0m",
        // "\033[96m⣤\033[0m",
        // "\033[96m⣆\033[0m",
        // "\033[96m⡇\033[0m",

        // "\033[38;5;196m⠏\033[0m",
        // "\033[38;5;154m⠛\033[0m",
        // "\033[38;5;118m⠹\033[0m",
        // "\033[38;5;82m⢸\033[0m",
        // "\033[38;5;51m⣰\033[0m",
        // "\033[38;5;93m⣤\033[0m",
        // "\033[38;5;129m⣆\033[0m",
        // "\033[38;5;201m⡇\033[0m",

        // "\033[38;5;196m◐\033[0m",
        // "\033[38;5;154m◓\033[0m",
        // "\033[38;5;118m◑\033[0m",
        // "\033[38;5;82m◒\033[0m",
        // "\033[38;5;51m◐\033[0m",
        // "\033[38;5;93m◓\033[0m",
        // "\033[38;5;129m◑\033[0m",
        // "\033[38;5;201m◒\033[0m",

        '[38;5;196m●[0m',
        '[38;5;202m●[0m',
        '[38;5;208m●[0m',
        '[38;5;214m●[0m',
        '[38;5;220m●[0m',
        '[38;5;226m●[0m',
        '[38;5;190m●[0m',
        '[38;5;154m●[0m',
        '[38;5;118m●[0m',
        '[38;5;82m●[0m',
        '[38;5;46m●[0m',
        '[38;5;47m●[0m',
        '[38;5;48m●[0m',
        '[38;5;49m●[0m',
        '[38;5;50m●[0m',
        '[38;5;51m●[0m',
        '[38;5;45m●[0m',
        '[38;5;39m●[0m',
        '[38;5;33m●[0m',
        '[38;5;27m●[0m',
        '[38;5;56m●[0m',
        '[38;5;57m●[0m',
        '[38;5;93m●[0m',
        '[38;5;129m●[0m',
        '[38;5;165m●[0m',
        '[38;5;201m●[0m',
        '[38;5;200m●[0m',
        '[38;5;199m●[0m',
        '[38;5;198m●[0m',
        '[38;5;197m●[0m',
    ],

    'message' => '',

    'bar_character' => '<info>✔</info>',
];
