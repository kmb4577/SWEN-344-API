<html>
 <body>

<?php
// Author(s): Kaitlin Brockway
//Create a RESTful client to read the API from another site
if (!isset($_GET["action"]))
{

}
elseif (isset($_GET["action"]) && $_GET["action"] == "new_book")
{
  //present the form for a NEW book that will be created on submit
  ?>
  <form action="BookClient.php" method="POST">

  		isbn: <input type="number" name="isbn"><br>
  		Title: <input type="text" name="title"><br>
      Publisher: <input type="text" name="publisher_name"<br>
      Price: <input type="number" name="price"<br>
      <!-- TODO_ change the type of the price if necessary -->
      Photo URL: <input type="number" name="thumbnail_url"<br>
      Quantity: <input type="number" name="count"<br>
      <input type="radio" name="available" value="true"> True
      <input type="radio" name="available" value="false"> False
      <input type="submit" value="Submit">
  <?php
}
elseif (isset($_GET["action"]) && $_GET["action"] == "edit_book")
{

}
elseif (isset($_GET["action"]) && $_GET["action"] == "create_book")
{
  $result = file_get_contents('http://www.se.rit.edu/~zjm1065/SWEN344-Assignments/REST/RESTAPI.php?action=multiply&v1=' . $_GET["v1"] . '&v2=' . $_GET["v2"]);
  // Retrieve & decode the necessary JSON information
  $result = json_decode($result, true);
  ?>
  <p>
  Value 1: <?php echo $_GET['v1']; ?>
  <br>Value 2: <?php echo $_GET['v2']; ?>
  <br>Operator: <?php echo $_GET['action']; ?>
  <br>Result: <?php echo $result; ?>
  </p>
  <?php
} ?>
 </body>
</html>