Tarp-Discover
=============

A standalone tool built for [Tarp.js](https://github.com/Zatnosk/Tarp.js).

About
-----
Tarp-Discover is a discovery proxy for the Tent protocol, made to ensure that JavaScript based apps can discover a Tent-entity's metapost, without depending on the server at the entity's location supporting CORS.

Read more about the discovery process in the Tent protocol in its [documentation](https://tent.io/docs/servers-entities#discovery).

Usage
-----
Tarp-Discover expects a query string with the parameter `entity` set to a URL encoded a Tent entity.

If a valid meta-post is found, it is returned as the only content in the reply.

If a valid meta-post is NOT found, a `404 Not Found` status is returned with an empty document.

If the parameter entity is not set, a `400 Bad Request` status is return, and this readme is shown.

Examples
--------

entity URL | ?entity=&lt;entityURL&gt;
-----------|--------------------
https://mytent.example.com|?entity=https%3A%2F%2Fmytent.example.com
https://tentserver.example/username|?entity=https%3A%2F%2Ftentserver.example%2Fusername
https://example.com/tent.php?user=username|?entity=https%3A%2F%2Fexample.com%2Ftent.php%3Fuser%3Dusername