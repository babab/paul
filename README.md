# Paul

Paul is a PHP Authorization of Users Library.

It is a 'pluggable' solution for adding login protected sections
for your PHP powered websites and offers safe password storage and
persistent logins using cookies.

To start using Paul you will only have to create a login and/or
registering form and add a few lines of code to your current project or
PHP script and have secure and easy user management.

**Current status: pre-alpha / scraping / not usable**

Paul is being rewritten with an orpan project as base: springwhiz-php It
was in the starting of that project when I needed a good and secure way
of authenticating users. springwhiz has been rewritten in Django, which
already has great built-in user authentication. Because I wanted to be
able to use these libraries I've originally written for springwhiz-php
in other projects, I've started Paul.


## Features

* Login / logout / registering of authorization accounts
* Protected against password cracking (using rainbow tables), by using
  a site-specific salt and a different salt for each users password
  digestion.
* Persistent login (using cookies)
* Logout (from all persistent login sessions)
* Uses MySQL as DBMS
* Easy installation of database tables
* Uses a table prefix to use multiple instances of Paul on a single
  database simultaneously
* You get an easy to use MySQL database query handler for free to use in
  your project.


## License

Copyright (c) 2012-2013  Benjamin Althues <benjamin@babab.nl>

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
