springwhiz
**********

springwhiz is a free webapplication that gives you some tools
to enhance your browsing experience. It is your personal start page
with quick access to favorites and other neat stuff.
If you prefix your query with an '@' or '#' symbol, springwhiz will
run a command or browse to a favorite respectively.

If the prefix is not found your query will be send directly to
the duckduckgo.com search engine.
This means you can use the awesome !bang syntax duckduckgo offers to
search on hundreds of sites directly.

springwhiz is open source software release under the ISC license.
You are encouraged to run it on your own webserver and modify/extend
it to your needs.


Usage
=====

Basic commands::

  command                      alias   description
  ---------------------------- ------- ---------------------------------------
  @register                    @reg    Will bring you to the register dialog
  @login [<username>]          @li     Will bring you to the login dialog
  @logout [all]                @lo     Log out (from all your sessions)
  @help                        @h      Show this help information
  @notepad                     @np     Open notepad
  @bookmark <subcommand>       @bm     Execute a bookmark command

Bookmark commands::

  command                              description
  ------------------------------------ ---------------------------------------
  #<label>                             Go to bookmark with <label>
  @bookmark add <label> <url>          Add bookmark
  @bookmark list                       List all bookmarks

Duckduckgo.com bang syntax search::

  bang                                 description
  ------------------------------------ ---------------------------------------
  \<string>                            Browse to 1st result for string
  !yt <string>                         Search YouTube
  !g <string>                          Search Google
  !i <string>                          Search Google Images
  !m <string>                          Search Google Maps
  !synonyms <string>                   Search thesaurus.com

Learn more about Duckduckgo's bang search - https://duckduckgo.com/?q=!bang


Installing
==========

springwhiz is still in an early development stage at the moment.
It already is a great tool for keeping bookmarks and a single
notepad though.

1. Clone git repository
2. Copy or move config.example.php to config.php and edit it
3. (s)FTP the (inner) springwhiz directory to your hosting environment
   or set it as the DocumentRoot of a virtual host configuration.
4. Go to http:// yourdomain.tpl [/springwhiz] /install.php in your
   browser
5. Optionally remove config.example.php and/or install.php


License
=======

Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>

Permission to use, copy, modify, and distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.


.. vim: set et ts=2 sw=2 sts=2:
