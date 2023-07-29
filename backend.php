<?php
require_once "configuration.php";
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}


$username = $_SESSION["username"];
$dead='';
$aging='';
$healthimg='';
$cleanimg='';
$happinessimg='';


function getUserId($connection, $username) {
    $sql = "SELECT id FROM user WHERE username = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        return $row["id"];
    } else {
       
        return null;
    }
}

$user_id = getUserId($connection, $username);

if ($user_id !== null) {
    $pet = fetchPetData($connection, $user_id);
} 

function createNewPet($connection, $user_id) {
    $sql = "INSERT INTO pet (health, happiness, clean, age, userid) VALUES (100, 100, 100, 0, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

function fetchPetData($connection, $user_id) {
    $sql = "SELECT * FROM pet WHERE userid = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        return $result->fetch_assoc();
    } else {
        createNewPet($connection, $user_id);
        return fetchPetData($connection, $user_id); 
    }
}

$pet = fetchPetData($connection, $user_id);

function isPetDead($pet) {
    return $pet["health"] <= 0 || $pet["happiness"] <= 0 || $pet["clean"] <= 0;
}
if (isPetDead($pet)) {
    
    $dead='Your pet is dead :(';
    $petImageUrl = "5.gif"; 
    $actionButtonsDisabled = "disabled";
    $clicked="";
} else {
    
        $petImageUrl = "2.gif";
        $actionButtonsDisabled = "";
        $clicked="disabled";
   
}
function updatePetStatus($connection, $pet_id, $health_change, $happiness_change, $clean_change) {
    $sql = "SELECT health, happiness, clean,age FROM pet WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

   
    if($health_change==100&&$happiness_change==100&&$clean_change==100){
        $petImageUrl = "2.gif";

        $health=100;
        $happiness=100;
        $clean=100;
        $age=0;
        $clicked="";
        $sql = "UPDATE pet SET health = ?, happiness = ?, clean = ?,age=? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iiiii", $health, $happiness, $clean,$age, $pet_id);
        $stmt->execute();
        return;

    }
    else{
        $clicked="disabled";
    }
    if ($data["health"] <= 0 || $data["happiness"] <= 0 || $data["clean"] <= 0) {
       
        $actionButtonsDisabled = "disabled";
        return;    
    }
   
    
    $health = min(100, $data["health"] + $health_change);
    $happiness = min(100, $data["happiness"] + $happiness_change);
    $clean = min(100, $data["clean"] + $clean_change);

    
    $health = max(0, $health);
    $happiness = max(0, $happiness);
    $clean = max(0, $clean);
   

    if($clean_change<0){
    $age=$data["age"]+1;}
    else{
        $age=$data["age"];
    }
  


    $sql = "UPDATE pet SET health = ?, happiness = ?, clean = ?,age=? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iiiii", $health, $happiness, $clean,$age, $pet_id);
    $stmt->execute();
}
function decreaseAttributes($connection, $pet_id) {
    $health_change = -3;
    $happiness_change = -3;
    $clean_change = -3;
    updatePetStatus($connection, $pet_id, $health_change, $happiness_change, $clean_change);
}
decreaseAttributes($connection, $pet["id"]);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    switch ($action) {
        case "feed":
            updatePetStatus($connection, $pet["id"], 10, 0, 0);
            break;
        case "play":
            updatePetStatus($connection, $pet["id"], 0, 10, 0);
            break;
        
         case "clean":
           updatePetStatus($connection, $pet["id"], 0, 0, 10);
           break;
         case "restart":
            $petImageUrl = "2.gif";
            updatePetStatus($connection, $pet["id"], 100, 100, 100);
            
            
            break;
        default:
            break;
        
    }
    if (isPetDead($pet)) {
       
        $dead='Your pet is dead :(';
        $petImageUrl = "5.gif";
        
        $actionButtonsDisabled = "disabled";
        $clicked="";
    } 

   
    $pet = fetchPetData($connection, $user_id);
}


?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
  background-image: url('sky.jpg');
}
</style>
    <title>Tamagotchi Game</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?php
             
            echo  'Click <a href="index.php">here</a> to logout';
        
        ?>
    </body>
</head>

<body>
    <h1>  </h1>
  
    <img id="pet-image" src="<?php echo $petImageUrl; ?>" alt="Tamagotchi Pet">
    <h2>  Status:   </h2>
    <h3><?php echo $dead; ?></h3>
    <p>Health:  <?php echo $pet["health"];if($pet["health"]<10){$healthimg="done.png";} 
    else if($pet["health"]<40){$healthimg="almost.png";} 
    else if($pet["health"]<80){$healthimg="medium.png";} 
    else{$healthimg="good.png";}
    
    
    
    ?> <img src="<?php echo $healthimg; ?>" alt="done"width="35" height="15"></p>
    <p>Happiness: <?php echo $pet["happiness"];if($pet["happiness"]<10){$happinessimg="done.png";} 
    else if($pet["happiness"]<40){$happinessimg="almost.png";} 
    else if($pet["happiness"]<80){$happinessimg="medium.png";} 
    else{$happinessimg="good.png";}
    
    
    
    ?> <img src="<?php echo $happinessimg; ?>" alt="done"width="35" height="15"></p>
    <p>Cleanliness: <?php echo $pet["clean"]; if($pet["clean"]<10){$cleanimg="done.png";} 
    else if($pet["clean"]<40){$cleanimg="almost.png";} 
    else if($pet["clean"]<80){$cleanimg="medium.png";} 
    else{$cleanimg="good.png";}
    
    
    
    ?> <img src="<?php echo $cleanimg; ?>" alt="done"width="35" height="15"></p>
    <p>Age: <?php echo $pet["age"];
     if( $pet["age"]>=0&& $pet["age"]<5){
        $aging=' ,your pet is a baby';
    }
    else
    if( $pet["age"]>=5&& $pet["age"]<12){
        $aging=' ,your pet is a child';
    }
    else
    if( $pet["age"]>=12&& $pet["age"]<18){
        $aging=' ,your pet is a teenager';
    }
    else
    if( $pet["age"]>=18&& $pet["age"]<50){
        $aging=' ,your pet is an adult';
    }
    else
    if( $pet["age"]>=50&& $pet["age"]<100){
        $aging=' ,your pet is a senior';
    }
     if (isPetDead($pet)){
        $aging='';}
     echo $aging; ?></p>

</body>
    <div class="button_container">
    <form method="post"action="process_action.php">
        <input type="hidden" name="action" value="feed">
        <?php
       
        if ($actionButtonsDisabled === "") {
            echo '<button type="submit">Feed</button>';
        }
        ?>
       
    </form>

    <form method="post"action="process_action.php">
        <input type="hidden" name="action" value="play">
        <?php
       
        if ($actionButtonsDisabled === "") {
           
            echo '<button type="submit">Play</button>';
        }
        ?>
       
    </form>

    <form method="post"action="process_action.php">
        <input type="hidden" name="action" value="clean">
        <?php
       
        if ($actionButtonsDisabled === "") {
            
            echo '<button type="submit">Clean</button>';
        }
        ?>
       
    </form>
    <form method="post" action="process_action.php">
        
        <input type="hidden" name="action" value="restart">
        <?php
     
        if ($clicked=="") {
           
            echo '<button id="btn2" type="submit">Restart</button>';
            
        }
        ?>
    </form>
    </div>
    <script>
    function autoRefreshPage() {
        var deadValue = "<?php echo $dead; ?>";
        if (deadValue === ''){
        location.reload();}
             
    }

    setInterval(autoRefreshPage, 1000);   
   
</script>   
</html>