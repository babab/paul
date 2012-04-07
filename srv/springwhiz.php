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

$sprwz_conf = parse_ini_file('../config', true);

if (empty($sprwz_conf))
    die("<p style\"color:red\"><strong>Error</strong>
            Could not load config file. Please copy 'config.example' to
            'config' and edit it.");

if (empty($sprwz_conf['main']['base_url']))
    die("springwhiz.php could not load the base_url setting");

require_once 'inc/bookmark.php';
require_once 'inc/command.php';

$settings = $sprwz_conf['main'];

if (!empty($_GET['cmd']))
  $cmd = command::getContent(htmlentities($_GET['cmd']));
else
  $cmd = command::getContent('');

if (!empty($_GET['q']))
  $q = htmlentities($_GET['q']);
else
  $q = '';
