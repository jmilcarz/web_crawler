<?php
     $search = $_GET['q'];

     $searche = explode(" ", $search);

     $x = 0;
     $construct = "";
     $params = array();

     foreach ($searche as $term) {
          $x++;
          if ($x == 1) {
               $construct .= "title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
          }else {
               $construct .= " AND title LIKE CONCAT('%',:search$x,'%') OR description LIKE CONCAT('%',:search$x,'%') OR keywords LIKE CONCAT('%',:search$x,'%')";
          }
          $params["search$x"] = $term;
     }

     $results = $pdo->prepare("SELECT * FROM `search_index` WHERE $construct");
     $results->execute($params);

?>
<!DOCTYPE html>
<html lang="en">
<head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <?php
     if ($search == "" || $search == "%20") {
          echo "<title>search suggle</title>";
     }else { ?>
          <title><?php echo $search; ?> | suggle search</title>
     <?php } ?>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
     <link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:400,600|Fira+Sans:400,600,800|Shrikhand" rel="stylesheet">
     <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
     <?php require('./modules/nav.php'); ?>


     <div class="container">
          <?php
          if ($results->rowCount() == 0) {
               echo "0 results found <hr>";
          }else {
               echo $results->rowCount() . " results found <br>";
          }
          foreach ($results->fetchAll() as $result) { ?>

          <div class="s-result">
               <h1><?php echo $result["title"]; ?></h1>
               <?php if ($result['description'] != "") { ?>
                    <p><?php echo $result["description"]; ?></p>
               <?php }else {echo "<p>We could not find the description of this page :/</p>";} ?>
               <a href="<?php echo $result['url']; ?>"><?php echo $result['url']; ?></a>
          </div>
          <?php } ?>
     </div>

</body>
</html>
