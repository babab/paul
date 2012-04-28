/*
 * Copyright (c) 2012 Benjamin Althues <benjamin@babab.nl>
 *
 * Permission to use, copy, modify, and distribute this software for any
 * purpose with or without fee is hereby granted, provided that the above
 * copyright notice and this permission notice appear in all copies.
 *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.
 */

$(document).ready(function(){
    $("#q").show("slow");
    $("#q").focus();

    menu = document.getElementById('menu');

    $("#q").keyup(function(){
        q = $("#q").val();

        switch (q[0]) {
        case sprwz_prefix_command:
            $("#q").css({
                'color': '#f70',
                'background-color': 'black'
            });
            if (!q[1])
                menu.innerHTML = "Entering command";
            else
                menu.innerHTML = "Entering command '" + q.substr(1) + "'";
            break;
        case sprwz_prefix_bookmark:
            $("#q").css({
                'color': '#f70',
                'background-color': 'white'
            });
            if (!q[1])
                menu.innerHTML = "Go to bookmark with label";
            else
                menu.innerHTML = "Go to bookmark with label '"
                        + q.substr(1) + "'";
            break;
        case '!':
            $("#q").css({
                'color': 'green',
                'background-color': 'white'
            });
            if (!q[1])
                menu.innerHTML = 'Searching using bang syntax';
            else
                menu.innerHTML = "Searching for '" + q.substr(1)
                        + "' using bang syntax";
            break;
        case '\\':
            $("#q").css({
                'color': 'green',
                'background-color': 'white'
            });
            menu.innerHTML = 'Go to the first result for: ' + q.substr(1);
            break;
        default:
            $("#q").css({
                'color': 'black',
                'background-color': 'white'
            });
            if (q == '')
                menu.innerHTML = "type '@help' to get started";
            else
                menu.innerHTML = 'Searching for: ' + q;
        }
    });
});
