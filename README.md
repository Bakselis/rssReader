# Reader for Rss and Flickr feeds

Project is build in one page fasion.
There are 2 endpoint "feed.php" and "post.php" for passing data to the front end
Front end part is build using javasrcipt and ajax calls on one page "index.html"

Feed list as array is stored in "feedList.php" file

## Unit Test

This project has one simple test build into it, to test the output of RssFeed class when providing "test.xml" file

Test can be run by command

```
./vendor/bin/phpunit --bootstrap vendor/autoload.php test/rssFeedTest
```
