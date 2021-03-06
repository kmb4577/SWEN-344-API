<?php

function book_store_switch()
{
	// Define the possible Book Store function URLs which the page can be accessed from
	$possible_function_url = array("getBook", "getSectionBooks", "createBook", "findOrCreatePublisher");

	if (isset($_GET["function"]) && in_array($_GET["function"], $possible_function_url))
	{
		switch ($_GET["function"])
		{
			case "createBook":
				if (isset($_GET["publisher_name"])){
					$pid = findOrCreatePublisher($_GET["publisher_name"], $_GET["address"], $_GET["website"]);
				}
				else{
					logError("createBook ~ Required parameters were not submited correctly.");
					return ("findOrCreatePublisher One or more parameters were not provided");
				}
				if (isset($_POST["isbn"]) &&
					isset($_POST["title"]) &&
					isset($_POST["price"]) &&
					isset($_POST["thumbnail_url"]) &&
					isset($_POST["available"]) &&
					isset($_POST["count"]) 
				)
					{	
					return createBook(
						$_POST["isbn"], 
						$_POST["title"],
						$pid,
						$_POST["price"],
						$_POST["thumbnail_url"],
						$_POST["available"],
						$_POST["count"]
						);
					}
				else{
					logError("createBook ~ Required parameters were not submited correctly.");
					return ("createBook One or more parameters were not provided");
				}
			case "findOrCreatePublisher":
				logError("log or create pub case");
				if (isset($_POST["publisher_name"])){
					$pid = findOrCreatePublisher($_POST["publisher_name"], $_POST["address"], $_POST["website"]);
					return $pid;
				}
			case "updateBook":
				if (isset($_GET["isbn"]) &&
					isset($_GET["title"]) &&
					isset($_GET["publisher_id"]) &&
					isset($_GET["price"]) &&
					isset($_GET["thumbnail_url"]) &&
					isset($_GET["available"]) &&
					isset($_GET["count"]))
					{
					return updateBook(
						$_POST["isbn"],
						$_POST["title"], 
						$_POST["publisher_id"], 
						$_POST["price"],
						$_POST["thumbnail_url"], 
						$_POST["available"], 
						$_POST["count"]);
					} else {
						logError("updateBook ~ Required parameters were not submitted correctly.");
						return ("One or more book parameters for updating this book were not provided.");
					}
			case "getBook":
				//if has params
				if (isset($_GET["isbn"])){
					return getBook($_GET["isbn"]);
				} else {
					logError("getBook ~ Required isbn parameter was not submitted correctly.");
					return ("getBook book isbn parameter was not submitted correctly.");
				}
				// return "Missing " . $_GET["param-name"]
			case "getSectionBooks":
				//if has params
				if (isset($_GET["section_id"])){
					return getSectionBooks($_GET["section_id"]);
				} else {
					logError("getBook ~ Required isbn parameter was not submitted correctly.");
					return ("getBook book isbn parameter was not submitted correctly.");
				}
		}
	}
}

//Define Functions Here
function createBook($isbn, $title, $publisher_id, $price, $thumbnail_url, $available, $count)
{
	logError("createBook ");

	try 
		{
			//$sqlite = new SQLite3($GLOBALS ["databaseFile"]);

			$sqlite = new SQLite3(getcwd(). "/../Database/SWEN344DB.db"); 
		
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("INSERT INTO Book (isbn, title, publisher_id, 
						price, thumbnail_url, available, count) VALUES (:isbn, :title, :publisher_id,
							:price, :thumbnail_url, :available, :count)");
							
			$query->bindParam(':isbn', $isbn);
			$query->bindParam(':title', $title);
			$query->bindParam(':publisher_id', $publisher_id);
			$query->bindParam(':thumbnail_url', $thumbnail_url);
			$query->bindParam(':price', $price);
			$query->bindParam(':available', $available);
			$query->bindParam(':count', $count);
			$result = $query->execute();
			return $result;
	}
	catch (Exception $exception)
	{
		if ($GLOBALS ["sqliteDebug"]) 
		{
			return $exception->getMessage();
		}
		logError($exception);
	}
}

function findOrCreatePublisher($name, $address, $website){
	logError("findorcreate ");
	try{
        echo "OPENING DATABASE";
        // echo __DIR__.DIRECTORY_SEPARATOR."Database/SWEN344DB.db";
        //$sqlite = new SQLite3(__DIR__.DIRECTORY_SEPARATOR."SWEN344DB.db");
		$sqlite = new SQLite3(__DIR__.DIRECTORY_SEPARATOR."SWEN344DB.db"); 
		echo "=============";
		$sqlite->enableExceptions(true);
		$pub_query = $sqlite->prepare("Select id from publisher where name=:name");
		$pub_query->bindParam(":name", $name);	//possible duplicate
		$publisher_id = $pub_query->execute();
        // echo $publisher_id;
		logError('outside of if statemet');
		$pub_id = $publisher_id->fetchArray();
		logError($pub_id[0]);
		if (empty($pub_id)){
			logError("inside if statement");
			$pub_query = $sqlite->prepare("INSERT INTO Publisher (name, address, website) 
				VALUES (:name, :address, :website)");
			$pub_query->bindParam(':name', $name);
			$pub_query->bindParam(':address', $address);
			$pub_query->bindParam(':website', $website);
			$pub_query->execute();
			$pub_query = $sqlite->prepare("Select id from publisher where name=:name");
			$publisher_id = $pub_query->execute();
			$pub_id = $publisher_id->fetchArray();			
		}
		
	}
	catch (Exception $exception)
	{
        echo "wtf!!!!!!!!!";
		// if ($GLOBALS ["sqliteDebug"]) 
		// {
		// 	return $exception->getMessage();
		// }
		// logError($exception);
        echo 'Caught exception: ',  $exception->getMessage(), "\n";
	}
    echo $pub_id;
	return $pub_id[0];
}

function updateBook($isbn, $title, $publisher_id, $price, $thumbnail_url, $available, $count)
{
	try 
		{
			$sqlite = new SQLite3($GLOBALS ["databaseFile"]);
			$sqlite->enableExceptions(true);
			
			//prepare query to protect from sql injection
			$query = $sqlite->prepare("INSERT INTO Book (isbn, title, published_by, 
						price, thumbnail_url, available, count) VALUES (:isbn, :title, :published_by,
							:price, :thumbnail_url, :available, :count)");
							
			$query->bindParam(':isbn', $isbn);
			$query->bindParam(':title', $title);
			$query->bindParam(':publisher_id', $publisher_id);
			$query->bindParam(':thumbnail_url', $thumbnail_url);
			$query->bindParam(':price', $price);
			$query->bindParam(':available', $available);
			$query->bindParam(':count', $count);
			$result = $query->execute();
		}
		catch (Exception $exception)
		{
			if ($GLOBALS ["sqliteDebug"]) 
			{
				return $exception->getMessage();
			}
			logError($exception);
	}
	return "TODO";
}

function getBook($isbn)
{
	return "TODO";
}

function getSectionBooks($section_id)
{
	return "TODO";
}

//return JSON array
// exit(json_encode($result));

?>