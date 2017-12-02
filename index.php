<?php $bgRandNumber = rand(1, 4); ?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <title>suggle search</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:400,600|Fira+Sans:400,600,800|Shrikhand" rel="stylesheet">
     <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
     <div id="searchEngine">
          <div class="search-engine-wrapper">
               <div class="search-engine-logo">
                    <h1>Suggle</h1>
               </div>
               <div class="search-engine-form">
                    <form action="search.php" method="get">
                         <input type="search" name="q" placeholder="Search suggle...">
                    </form>
                    <ul class="links">
                         <li><a href="https://www.google.com"><i class="fa fa-google"></i></a></li>
                         <li><a href="https://www.youtube.com"><i class="fa fa-youtube-play"></i></a></li>
                         <li><a href="https://www.facebook.com"><i class="fa fa-facebook"></i></a></li>
                         <li><a href="https://www.twitter.com"><i class="fa fa-twitter"></i></a></li>
                         <li><a href="https://www.twitch.com"><i class="fa fa-twitch"></i></a></li>
                         <li><a href="https://www.trello.com"><i class="fa fa-trello"></i></a></li>
                         <li><a href="https://www.github.com"><i class="fa fa-github"></i></a></li>
                    </ul>
               </div>
          </div>
     </div>
</body>
</html>
