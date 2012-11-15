Plugin for YOURLS 1.5.1+: Cache Stats pages

# What for

Rudimentary caching system so stats page (eg http://sho.rt/blah+) are generated once every XX hours and served from cache otherwise.

# How to

* In `/user/plugins`, create a new folder named `cache-stats-pages`
* Drop these files in that directory
* Go to the Plugins administration page and activate the plugin 
* Have fun

# Disclaimer

This is a rudimentary caching system.

It is used on http://yourls.org/ (for instance http://yourls.org/cookie+ -- check the source) but there is a lot of room for improvements (garbage collection for example)
