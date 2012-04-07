<?php
# Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
#
# Permission to use, copy, modify, and distribute this software for any
# purpose with or without fee is hereby granted, provided that the above
# copyright notice and this permission notice appear in all copies.
#
# THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
# WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
# ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
# WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
# ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
# OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.

class command {

    public static $commandlist = array(
        'h'         => 'help',
        'he'        => 'help',
        'hel'       => 'help',
        'help'      => 'help',
        'unknown'   => 'help',
    );

    public static function getContent($cmd)
    {
        if (empty($cmd))
            return '';

        if (array_key_exists($cmd, static::$commandlist))
            $command = static::$commandlist[$cmd];
        else
            $command = '_doesnotexist';

        switch ($command) {
        case 'help':
            return file_get_contents('./html/help.html');
            break;
        default:
            return "<span class=\"warn\">Warning </span> "
                . "Did not recognize '@$cmd' as a valid command!"
                . file_get_contents('./html/help.html');
        }
    }
}
