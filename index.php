<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Rss Feed</title>

  <!-- Bootstrap core CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">


  <style media="screen">
    #post{
      margin-top: 50px;
      -webkit-transition: opacity .5s ease-in-out;
      -moz-transition: opacity .5s ease-in-out;
      -ms-transition: opacity .5s ease-in-out;
      -o-transition: opacity .5s ease-in-out;
      transition: opacity .5s ease-in-out;
    }
  </style>
</head>

<body>

  <!-- Page Content -->
  <div class="container">

    <div class="row" id="main">
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Rss Feed 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <script>

    var xhr;
    var xhr2;
    if (window.XMLHttpRequest){
        xhr = new XMLHttpRequest();
        xhr2 = new XMLHttpRequest();
    }else if (window.ActiveXObject){
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
        xhr2 = new ActiveXObject("Msxml2.XMLHTTP");
    }else
        throw new Error("Ajax is not supported by your browser");



    var urlParams = new URLSearchParams(window.location.search);
    var feed = "";
    if(urlParams.get('feed') !== null){
      var feed = urlParams.get('feed');
    }

    var postGet = "";
    if(urlParams.get('post') !== null){
      var postGet = urlParams.get('post');
    }

    if(postGet != ""){
      // post page
      document.getElementById('main').innerHTML += '<div class="col-md-8" id="post"></div>';
      document.getElementById('main').innerHTML += '<div class="col-md-4" id="sidebar"></div>';

      // get post content
      var parameters = 'feed='+feed+'&post='+encodeURIComponent(postGet);
      updatePost(parameters, '?'+parameters, false);

      // get sidebar content
      xhr2.onreadystatechange = function () {

          if (xhr2.readyState < 4){

          }
          else if (xhr2.readyState === 4) {
              if (xhr2.status == 200 && xhr2.status < 300)
              {
                var json = JSON.parse(xhr2.responseText);

                var sidebar = document.getElementById('sidebar');
                json.forEach(function (item, index) {
                  var feed_text =
                    '<div class="card my-4">'+
                        '<h5 class="card-header">'+item.title+'</h5>'+
                          '<div class="card-body">'+
                            '<ul>';

                  item.posts.forEach(function (post, index) {
                    feed_text += '<li><a href="'+post.url+'">'+post.title+'</a></li>';
                  });

                  feed_text +=
                          '</ul>'+
                        '</div>'+
                      '</div>';

                  sidebar.innerHTML += feed_text;
                });
              }
          }
      }

      xhr2.open('GET', 'endpoints/feed.php');
      xhr2.send();


    }else{
      // feed page

      document.getElementById('main').innerHTML = '<div class="col-md-12" id="feed"></div>';

      xhr.onreadystatechange = function () {
          if (xhr.readyState < 4){

          }
          else if (xhr.readyState === 4) {
              if (xhr.status == 200 && xhr.status < 300)
              {
                var json = JSON.parse(xhr.responseText);

                var feed = document.getElementById('feed');
                json.forEach(function (item, index) {
                  var title_text = '<h2 class="my-4">'+ item.title +'</h2>';

                  feed.innerHTML += title_text;

                  item.posts.forEach(function (post, index) {
                    var post_text =
                    '<div class="card mb-4">'+
                      '<div class="card-body">'+
                        '<h3 class="card-title">'+post.title+'</h2>'+
                        '<p class="card-text">'+post.description+'</p>'+
                        '<a href="'+post.url+'" class="btn btn-primary">Read More &rarr;</a>'+
                      '</div>'+
                    '</div>'
                    feed.innerHTML += post_text;
                  });
                });
              }
          }
      }

      xhr.open('GET', 'endpoints/feed.php?feed=' + feed);
      xhr.send();
    }


    function addPostNav(){
      // add functionality to post navigation
      var anchors = document.querySelectorAll("a.nav");
      for(i=0; i<anchors.length; i++){
          anchors[i].addEventListener('click', function(e){
            e.preventDefault();
            // get relatice url
            var href = e.target.href;
            href = href.split('?');
            parameters = href[1];
            href = '?'+parameters;
            updatePost(parameters, href, true);
          });
      }

    }


    function updatePost(parameters, href, urlChange){
      var xhr;
      if (window.XMLHttpRequest){
          xhr = new XMLHttpRequest();
      }else if (window.ActiveXObject){
          xhr = new ActiveXObject("Msxml2.XMLHTTP");
      }else
          throw new Error("Ajax is not supported by your browser");

      // get post content
      xhr.onreadystatechange = function () {

          if (xhr.readyState < 4){

          }
          else if (xhr.readyState === 4) {
              if (xhr.status == 200 && xhr.status < 300)
              {
                var post_div = document.getElementById('post');
                // fade out
                var timeDelay = 0;
                if(urlChange){
                  post_div.style.opacity = 0;
                  timeDelay = 500;
                }
                setTimeout(function(){
                    // wait for animation to end

                    var json = JSON.parse(xhr.responseText);

                    var post = json.post;

                    var post_text =
                            '<h3>'+post.date+' / '+ json.feedTitle +'</h3>'+
                            '<h1 class="card-title">'+post.title+'</h1>';

                    if(post.image != ""){
                            post_text += '<div class="row">'+
                                              '<div class="col-md-7"><img class="img-fluid" src="'+post.image+'"></div>'+
                                              '<div class="col-md-5"><p class="card-text">'+post.description+'</p></div>'+
                                          '</div>';
                    }else{
                            post_text += '<p class="card-text">'+post.description+'</p>';
                    }

                    post_text += '<div class="row">'+
                            '<div class="col-md-4">';
                    if(json.previousPostUrl != ""){
                            post_text += '<a class="nav" href="'+json.previousPostUrl+'">Previous Article</a>';
                    }

                            post_text += '</div>';

                            post_text += '<div class="col-md-4"><a href="'+json.feedUrl+'">View Entire Feed</a></div>';

                            '<div class="col-md-4">';
                    if(json.nextPostUrl != ""){
                            post_text += '<a class="nav" href="'+json.nextPostUrl+'">Next Article</a>';
                    }

                            post_text += '</div>';
                              '<div class="col-md-4"><a class="nav" href="'+json.nextPostUrl+'">Next Article</a></div>'+
                            '</div>';
                    post_div.innerHTML = post_text;

                    addPostNav();
                    // fade in
                    if(urlChange){
                      post_div.style.opacity = 1;
                    }
                },timeDelay)

                // checking if url needs to be changed
                if(urlChange){
                  window.history.pushState(post.title, post.title, href);
                }

              }
          }
      }

      var url = 'endpoints/post.php?'+parameters;
      xhr.open('GET', url);

      xhr.send();
    }
  </script>
</html>
