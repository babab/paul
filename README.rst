springwhiz
**********

springwhiz is a free webapplication that gives you some tools
to enhance your browsing experience. It is your personal start page
with quick access to favorites and other fun stuff.

You can search the web using the very great duckduckgo.com search engine
by entering you search string right now.
If you prefix your query with an '@' or '#' symbol, springwhiz will
run a command or browse to a favorite respectively.

If the prefix is not found your query will be send directly to
duckduckgo.com

This means you can use the awesome !bang syntax duckduckgo offers to search
on hundreds of sites directly.

springwhiz is free software release under the ISC license.
You are encouraged to run it on your own (local) webserver.


Help
====

Basic commands::

  command                      alias   description
  ---------------------------- ------- ---------------------------------------
  @register                    @reg    Will bring you to the register dialog
  @login [<username>]          @li     Will bring you to the login dialog
  @logout                      @lo     Log out
  @help                        @h      Show this help information
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


.. vim: set et ts=2 sw=2 sts=2:
